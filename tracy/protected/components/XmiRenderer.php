<?php

class XmiRenderer
{
    private $base_id = 'UMLModel.1';
    private $extra_data_types = array();
    private $open_elements=array();
    
    private function package_id($package)
    {
        if ( $package == null )
            return $this->base_id;
        return "UMLPackage.$package";
    }
    
    private function class_id($class)
    {
        return "UMLClass.$class";
    }
    
    private function generalization_id($parent,$child)
    {
        return "UMLGeneralization.$parent.$child";
    }
    
    private function attribute_id($attribute)
    {
        return "UMLAttribute.$attribute";
    }
    
    private function method_id($method)
    {
        return "UMLOperation.$method";
    }
    
    private function argument_id($argument)
    {
        return "UMLParameter.$argument";
    }
    
    private function type_id($name)
    {
        if ( array_key_exists($name,$this->extra_data_types) )
            return $this->extra_data_types[$name];
        
        $qual = explode("::",$name);
        $unqual = $qual[count($qual)-1];
        $classes = Class_Prog::model()->findAllByAttributes(array('name'=>$unqual));
        foreach ( $classes as $class )
        {
            if ( $class->name == $name || $class->full_name() == $name )
                return $this->class_id($class->id_class);
        }
        
        return $this->extra_data_types[$name] = $this->generic_id(); // "X.".count($this->extra_data_types);
    }
    
    private function association_id($association)
    {
        return "UMLAssociation.$association";
    }
    
    private function association_end_id($association,$n)
    {
        return "UMLAssociationEnd.$association.$n";
    }
    
    private function generic_id($name='X')
    {
        static $x = 0;
        $x++;
        return "$name.$x";
    }
    
    private function open_element($name,$attrs=array())
    {
        echo str_repeat(' ',2*count($this->open_elements));
        $this->open_elements[]=$name;
        echo "<$name";
        if ( count($attrs) > 0 )
        {
            echo ' ';
            foreach($attrs as $name=>$value)
            {
                echo "$name=\"".htmlspecialchars($value).'" '; 
            }
        }
        echo ">\n";
    }
    
    private function close_element()
    {
        $last = array_pop($this->open_elements);
        echo str_repeat(' ',2*count($this->open_elements));
        echo "</$last>\n";
    }
    
    private function empty_element($name,$attrs=array())
    {
        echo str_repeat(' ',2*count($this->open_elements));
        echo "<$name";
        if ( count($attrs) > 0 )
        {
            echo ' ';
            foreach($attrs as $name=>$value)
            {
                echo "$name=\"".htmlspecialchars($value).'" '; 
            }
        }
        echo "/>\n";
    }
    
    private function simple_element($name,$cdata)
    {
        echo str_repeat(' ',2*count($this->open_elements));
        echo "<$name>".htmlspecialchars($cdata)."</$name>\n";
    }
    
    private function open_uml_element($element_name,$id,$name,$namespace,
                                      $attrs=array(),$visibility='public')
    {
        $attrs['xmi.id']=$id;
        $attrs['visibility']=$visibility;
        $attrs['name']=$name;
        if ( $namespace != null )
            $attrs['namespace']=$namespace;
        /*$attrs['isSpecification']="false";
        $attrs['isRoot']="false";
        $attrs['isLeaf']="false";
        $attrs['isAbstract']="false";*/
        return $this->open_element("UML:$element_name",$attrs);
    }
    
    
    private function uml_empty_element($element_name,$id,$name,$namespace,
                                       $attrs=array(),$visibility='public')
    {
        $attrs['xmi.id']=$id;
        $attrs['visibility']=$visibility;
        $attrs['name']=$name;
        if ( $namespace != null )
            $attrs['namespace']=$namespace;
        /*$attrs['isSpecification']="false";
        $attrs['isRoot']="false";
        $attrs['isLeaf']="false";
        $attrs['isAbstract']="false";*/
        return $this->empty_element("UML:$element_name",$attrs);
    }
    
    function open_uml_simple_element($element_name)
    {
        return $this->open_element("UML:$element_name");
    }
    
    function begin( $xmi_version="1.0", $uml_version="1.3" )
    {
        echo "<?xml version='1.0' encoding='UTF-8'?>\n";
        $this->open_element('XMI',array('xmi.version'=>$xmi_version,
                            'xmlns:UML'=>"href://org.omg/UML/$uml_version"));
        $this->open_element('XMI.header');
            $this->open_element('XMI.documentation');
                $this->simple_element('XMI.owner',"Don't Panic");
                $this->simple_element('XMI.contact',"dont.panic.swe@gmail.com");
                $this->simple_element('XMI.exporter','Tracy');
                $this->simple_element('XMI.exporterVersion','42');
                $this->simple_element('XMI.notice','');
            $this->close_element();
            $this->empty_element('XMI.metamodel', array('xmi.name'=>"UML",
                                                'xmi.version'=>"$uml_version"));
        $this->close_element();
        $this->open_element('XMI.content');
        $this->open_uml_element('Model',$this->base_id,"Design Model","UMLProject.1");
    }
    
    function end()
    {
        $this->close_element();//model
        $this->extra_types();
        while(count($this->open_elements)>0)
            $this->close_element();
    }
    
    
    function package(Package$package,$assoc)
    {
        $package->with('parent0, packages, classes');
        $id = $this->package_id($package->id_package);
        $namespace = $this->package_id($package->parent);
        
        $this->open_uml_simple_element('Namespace.ownedElement');
        $this->open_uml_element('Package',$id,$package->name,$namespace);
        $this->open_uml_simple_element('Namespace.ownedElement');
        
        foreach($package->packages as $c)
            $this->package($c,$assoc);
        foreach($package->classes as $c)
            $this->print_class($c,$assoc);
        
        if ( $assoc )
        {
            $ass = Association::model()->findAll(
                array('join'=>'Join class c on c.id_class = class_from',
                      'condition'=>'id_package = :idp',
                      'params'=>array('idp'=>$package->id_package)
                )
            );
            foreach($ass as $hole)
                $this->association($hole,$id);
        }
        
        $sig = Connect::model()->findAll(
            array('join'=>'Join method sl on sl.id_method = t.slot
                           join class c on c.id_class = sl.id_class',
                  'condition'=>'c.id_package = :idp',
                  'params'=>array('idp'=>$package->id_package)
            )
        );
        foreach($sig as $segv)
            $this->signal($segv,$id);
        
        foreach ($package->packages as $child)
        {
            $dep = $child->get_dependencies(false);
            $child_id = $this->package_id($child->id_package);
            foreach ( $dep as $d )
            {
                if ( $d['id_from'] != $child->id_package )
                    continue;
                
                $did = $this->generic_id('UMLDependency');
                $dit = $this->package_id($d['id_to']);
                $this->open_uml_element('Dependency',"",$child_id, null, array(
                                                                'client'=>$id,
                                                                'supplier'=>$dit));
            }
        }
        
        $this->close_element();
        $this->close_element();
        $this->close_element();
    }
    
    function print_class(Class_Prog$class,$assoc)
    {
        $class->with('children,parents,members,methods,package');
        $id = $this->class_id($class->id_class);
        $ns = $this->package_id($class->id_package);
        
        $pecialization = "";
        foreach($class->children as $child)
        {
            $pecialization .= " ".$this->generalization_id($class->id_class,
                                                      $child->id_class);
        }
        
        $generalization = "";
        foreach($class->parents as $parent)
        {
            $generalization .= " ".$this->generalization_id($parent->id_class,
                                                      $class->id_class);
        }
        
        $this->open_uml_element(
                $class->type == 'interface' ? 'Interface'  : 'Class',
                $id, $class->name,$ns, array(
                            'specialization'=>$pecialization,
                            'generalization'=>$generalization,
                            'isActive'=>"false",
                            'isAbstract'=>$class->type=='class'?'false':'true'
                        )
            );
        $this->open_uml_simple_element('Classifier.feature');

        foreach ( $class->members as $attribute)
            $this->attribute($attribute,$assoc);
        foreach ( $class->methods as $method)
            $this->method($method);
            
        $this->close_element();
        $this->close_element();
        
        foreach($class->parents as $parent)
        {
            $idg = $this->generalization_id($parent->id_class, $class->id_class);
            $this->uml_empty_element('Generalization',$idg,"",$ns,array(
                    'child' => $id,
                    'parent' => $this->class_id($parent->id_class)
                )
            );
        }
    }
    
    function attribute(Attribute$attribute,$assoc)
    {
        $attribute->with('association');
        if ( isset($attribute->association) && $assoc )
        {
            return;
        }
        
        $id = $this->attribute_id($attribute->id_attribute);
        $scope = $attribute->static ? 'classifier' : 'instance';
        
        $this->uml_empty_element('Attribute', $id, $attribute->name, null,
                            array('scope'=>$scope,
                                  'changeability'=>"changeable",
                                  'targetScope'=>"instance",
                                  'ownerScope'=>$scope,
                                  'type'=> $this->type_id($attribute->type),
                                  'owner'=>$this->class_id($attribute->id_class)
                            ),
                            $attribute->access
                        );
    }
    
    function extra_types()
    {
        foreach ( $this->extra_data_types as $name => $id )
            $this->uml_empty_element('DataType',$id,$name,null);
    }
    
    function association(Association $association,$namespace)
    {
        $id = $this->association_id($association->id_association);
        $association->with('attribute');
        $end_1 = $this->association_end_id($association->id_association,1);
        $end_2 = $this->association_end_id($association->id_association,1); 
        
        $this->open_uml_element('Association',$id,"",$namespace);
            $this->open_uml_simple_element('Association.connection');
                $this->uml_empty_element('AssociationEnd',$end_1,"", null,
                        array('isNavigable'=>"false",
                              'ordering'=>"unordered",
                              'aggregation'=>$association->aggregation_from,
                              'targetScope'=>"instance",
                              'changeability'=>"changeable",
                              'association'=>$id,
                              'type'=>$this->class_id($association->class_from)
                        ),
                        $association->attribute->access
                    );
                $this->uml_empty_element('AssociationEnd',$end_2,
                        $association->attribute->name, null,
                        array('isNavigable'=>"true",
                              'ordering'=>"unordered",
                              'aggregation'=>$association->aggregation_to,
                              'targetScope'=>"instance",
                              'changeability'=>"changeable",
                              'association'=>$id,
                              'type'=>$this->class_id($association->class_to)
                        ),
                        "private"
                    );
            $this->close_element();
        $this->close_element();
    }
    
    function method(Method$method)
    {
        $method->with('arguments');
        $id = $this->method_id($method->id_method);
        $scope = $method->static ? 'classifier' : 'instance';
        $abstract = $method->abstract ? 'true' : 'false';
        $class_id = $this->class_id($method->id_class);
        
        $this->open_uml_element('Operation',$id,$method->name,null,array(
                'ownerScope'=>$scope,
                'isQuery'=>"false",
                'isAbstract'=>$abstract,
                "concurrency"=>"sequential",
                "specification"=>"",
                "owner"=>$class_id,
            ),
            $method->access
        );
        
        $this->open_uml_simple_element('BehavioralFeature.parameter');
        foreach($method->arguments as $arg)
            $this->argument($arg);
            
        $this->uml_empty_element('Parameter',$id,'return',null,array(
                    'kind'=>'return',
                    'behavioralFeature'=>$id,
                    'type'=>$this->type_id($method->return)
                )
            );
        $this->close_element();
        
        $this->close_element();
    }
    
    function argument(Argument $argument)
    {
        $id = $this->argument_id($argument->id_argument);
        $type_id = $this->type_id($argument->type);
        $method_id = $this->method_id($argument->id_method);
        
        $this->uml_empty_element('Parameter',$id,$argument->name,null,array(
                    'kind'=>$argument->direction,
                    'behavioralFeature'=>$method_id,
                    'type'=>$type_id
                )
            );
    }
    
    function signal(Connect $connect,$namespace)
    {
        $connect->with('signal0','slot0');
        $connect->signal0->with('class');
        $connect->slot0->with('class');
        $this->open_uml_element('Dependency',
                                $this->generic_id('Dependency'),
                                $connect->signal0->name.'()',
                                $namespace,
                                array(
                                    'client'=>$this->class_id(
                                                $connect->slot0->id_class),
                                    'supplier'=>$this->class_id(
                                                $connect->signal0->id_class),
                                )
                            );     
    }
}