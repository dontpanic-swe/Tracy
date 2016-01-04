<?php

Yii::import('zii.widgets.grid.CGridView');
    
class IDGridView extends CGridView
{
    
    public $id_field = 'id';
    public $id_prefix = 'row';
    
    /**
     * Renders a table body row.
     * @param integer $row the row number (zero-based).
     */
    public function renderTableRow($row)
    {
        $data=$this->dataProvider->data[$row];
        
        $id_field = $this->id_field;
        $id = 'id="'.$this->id_prefix.$data->$id_field.'"';
        
        if($this->rowCssClassExpression!==null)
        {
                echo "<tr $id class='".$this->evaluateExpression($this->rowCssClassExpression,array('row'=>$row,'data'=>$data))."'>";
        }
        else if(is_array($this->rowCssClass) && ($n=count($this->rowCssClass))>0)
                echo "<tr $id class='".$this->rowCssClass[$row%$n]."'>";
        else
                echo "<tr $id>";
        foreach($this->columns as $column)
                $column->renderDataCell($row);
        echo "</tr>\n";
    }

};