<?php
    define('INT_MIN', ~PHP_INT_MAX);
    
    class data_set
    {
        public $name, $color, $data;
        
        function data_set($name,$color,$data)
        {
            $this->name = $name;
            $this->color = $color;
            
            if ( is_array($data) )
                $this->data = $data;
            else
                $this->data = explode("\n",$data);
        }
        
        function min_data()
        {
            return min($this->data);
        }
        
        function max_data()
        {
            return max($this->data);
        }
    }
    
    class grid_style
    {
        const NO_GRID    = 0x00;
        const BEHIND     = 0x01;
        const OVERLAY    = 0x02;
        
        
        const NO_TEXT    = 0x00;
        const TEXT_TOP   = 0x10;
        const TEXT_BOTTOM= 0x20;
        
        const VISIBILITY = 0x0F;
        const TEXT       = 0xF0;
    }
    
    class graph
    {
        public $x_labels, $data;
        
        
        
        
        function graph( $x_labels=array(), $data=array())
        {
            if ( is_array($x_labels) )
                $this->x_labels = $x_labels;
            else
                $this->x_labels = explode("\n",$x_labels);
                
            $this->data = $data;
        }
        
        private function MoveTo($x,$y)
        {
            return "M $x,$y ";
        }
        private function LineTo($x,$y)
        {
            return "L $x,$y ";
        }
        private function Close()
        {
            return 'Z ';
        }
        
        private function render_grid($min_x, $delta_x,$min_y,$max_y,
                                     $text_style=grid_style::TEXT_TOP)
        {
            $curr_x = $min_x;
            if ( $text_style & grid_style::TEXT_TOP )
                $text_y = $max_y;
            else
                $text_y = $min_y;
                
            foreach ( $this->x_labels as $label)
            {
                echo "<line class='grid' x1='$curr_x' y1='$min_y' x2='$curr_x' y2='$max_y' />";
                if ( $text_style & grid_style::TEXT )
                   echo "<text class='grid_label' x='$curr_x' y='$text_y'>$label</text>";
                $curr_x += $delta_x;
            }
        }
        
        function render ( $x, $y, $width, $height,
                         $filled = false, $grid_style = 0x11 )
        {
            $min = PHP_INT_MAX; // Min value
            $max = INT_MIN; // Max value
            foreach($this->data as $data_set)
            {
                $min = min($min,$data_set->min_data());
                $max = max($max,$data_set->max_data());
            }
            $min_y = $y+$height+2;// Y pos of min value
            $max_y = $y;        // Y pos of max value
            $min_x = $x;        // X pos of first value
            $max_x = $x+$width;// X pos of last valus
            $delta_x = ($max_x-$min_x)/(count($this->x_labels)-1);
            $delta_y = ($max_y-$min_y)/($max-$min);
            
            if ( $grid_style & grid_style::BEHIND )
                $this->render_grid($min_x, $delta_x,$min_y,$max_y,$grid_style);
            
            foreach($this->data as $data_set)
            {
                echo "<path d='";
                $curr_x = $min_x;
                $skip = true;
                foreach ( $data_set->data as $data_point )
                {
                    if ( !isset($data_point) )
                    {
                        $skip=true;
                    }
                    else if ( $skip )
                    {
                        echo $this->MoveTo($curr_x,$min_y+($data_point-$min)*$delta_y);
                        $skip = false;
                    }
                    else
                    {
                        echo $this->LineTo($curr_x,$min_y+($data_point-$min)*$delta_y);
                    }
                    $curr_x += $delta_x;
                }
                if ( $filled )
                    echo $this->LineTo($max_x,$min_y).
                        $this->LineTo($min_x,$min_y).
                        $this->Close().
                        "' style='stroke:none;fill:{$data_set->color}'";
                else
                    echo "' style='stroke:{$data_set->color};fill:none'";
                echo " class='plot' />\n";
            }
            
            if ( $grid_style & grid_style::OVERLAY )
                $this->render_grid($min_x, $delta_x,$min_y,$max_y, $grid_style);
        }
        
        function legend ( $x, $y, $width, $height, $align_right=false )
        {
            $min_y = $y;
            $max_y = $y+$height;
            $min_x = $x;
            $max_x = $x+$width;
            $delta_y = ($max_y-$min_y)/(count($this->data)-1);
            $curr_y = $min_y;
            foreach($this->data as $data_set)
            {
                $line_y=$curr_y+2;
                echo "<text class='legend_label' ".
                    ( $align_right ?
                        "x='$max_x' text-anchor='end' " :
                        "x='$min_x' "
                    ) .
                    "y='$curr_y'>{$data_set->name}</text>";
                echo "<line x1='$min_x' y1='$line_y' ".
                            "x2='$max_x' y2='$line_y' ".
                            "style='stroke:{$data_set->color};fill:none' ".
                            "class='legend_marker' ".
                        "/>";
                $curr_y += $delta_y;
            }
        }
    }
    
    /* Ritorna un colore arbitrario (modulo $n) */
    function color($n)
    {
        $colors = array ( 'red', 'blue', 'gray', 'yellow', 'purple', 'green',
                        'cyan',  'magenta', 'orange', 'black' );
        return $colors[abs($n)%count($colors)]; 
    }

    