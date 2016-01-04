<?php


    $this->breadcrumbs = array(
        'Upload source'
    );

function register_script_file($name)
{
    $base = Yii::app()->getRequest()->getHostInfo('').Yii::app()->getUrlManager()->getBaseUrl();
    Yii::app()->clientscript->registerScriptFile("$base/$name");
}
function register_css_file($name)
{
    $base = Yii::app()->getRequest()->getHostInfo('').Yii::app()->getUrlManager()->getBaseUrl();
    Yii::app()->clientscript->registerCssFile("$base/$name");
}

register_script_file("js/lib/codemirror.js");
register_script_file("js/lib/util/matchbrackets.js");
register_script_file("js/mode/clike/clike.js");
//register_script_file("js/lib/util/foldcode.js");

register_css_file("js/lib/codemirror.css");
Yii::app()->clientscript->registerCss('codemirror',
            '.CodeMirror {border: 2px inset #dee; }');

register_css_file('js/lib/util/simple-hint.css');
register_script_file('js/lib/util/simple-hint.js');
register_script_file("js/lib/util/cxx-hint.js");

$themes = array(
    'ambiance-mobile',
    'ambiance',
    'blackboard',
    'cobalt',
    'eclipse',
    'elegant',
    'erlang-dark',
    'lesser-dark',
    'monokai',
    'neat',
    'night',
    'rubyblue',
    'solarized',
    'twilight',
    'vibrant-ink',
    'xq-dark',
);
foreach ( $themes as $t )
    register_css_file("js/theme/$t.css");

    echo CHtml::beginForm(array('view'));
    $old_code = '';
    if ( isset($_REQUEST['edit']) && $_REQUEST['edit'] &&
            isset(Yii::app()->session['code']) )
        $old_code = Yii::app()->session['code'];
    echo CHtml::textArea('code',$old_code,array('id'=>'code'));
    echo CHtml::submitButton('Upload');
    echo CHtml::endForm();
?>

<p>Select a theme: <select onchange="selectTheme()" id="select_theme">
    <option selected="selected">default</option>
    <?php
        foreach ( $themes as $t )
            echo "<option>$t</option>";
    ?>
</select>
</p>

<?php
function ns_build_package(Package$package,$namespace,&$namespace_array)
{
    $namespace_array []= $namespace.$package->name;
    $package->with('packages,classes');
    
    $sub_scope = $namespace.$package->name.'::';
    
    if ( isset($package->packages) )
        foreach($package->packages as $c)
            ns_build_package($c,$sub_scope,$namespace_array);
    if ( isset($package->classes) )
        foreach($package->classes as $class)
            $namespace_array []= $sub_scope.$class->name;
}



$namespace_array = array();
$packages = Package::model()->findAll('parent is null');
foreach($packages as $p)
    ns_build_package($p,"",$namespace_array);

?>

<script>
    //highlight
    var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
        lineNumbers: true,
        matchBrackets: true,
        mode: "text/x-c++hdr",
        extraKeys: {"Ctrl-Space": "autocomplete"},
        indentUnit: 4,
        indentWithTabs: true,
        theme: 'default',
    });
    
    // theme
    var input = document.getElementById("select_theme");
    function selectTheme()
    {
        var theme = input.options[input.selectedIndex].innerHTML;
        editor.setOption("theme", theme);
    }
    //editor.setOption('theme','default');
  
    //complete
    var cxxNamespaceHints = <?php echo json_encode($namespace_array); ?>;
    CodeMirror.commands.autocomplete = function(cm)
    {
        CodeMirror.simpleHint(cm, CodeMirror.cxxHint);
    }
  
    //folding
    /*var foldFunc = CodeMirror.newFoldFunction(CodeMirror.braceRangeFinder);
    editor.on("gutterClick", foldFunc);
    foldFunc(editor, 9);*/
  
  
</script>
