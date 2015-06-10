<?php

class Code39 {
    
    private $Code39 = array(
        '0'=>'111221211',
        '1'=>'211211112',
        '2'=>'112211112',
        '3'=>'212211111',
        '4'=>'111221112',
        '5'=>'211221111',
        '6'=>'112221111',
        '7'=>'111211212',
        '8'=>'211211211',
        '9'=>'112211211',
        'A'=>'211112112',
        'B'=>'112112112',
        'C'=>'212112111',
        'D'=>'111122112',
        'E'=>'211122111',
        'F'=>'112122111',
        'G'=>'111112212',
        'H'=>'211112211',
        'I'=>'112112211',
        'J'=>'111122211',
        'K'=>'211111122',
        'L'=>'112111122',
        'M'=>'212111121',
        'N'=>'111121122',
        'O'=>'211121121',
        'P'=>'112121121',
        'Q'=>'111111222',
        'R'=>'211111221',
        'S'=>'112111221',
        'T'=>'111121221',
        'U'=>'221111112',
        'V'=>'122111112',
        'W'=>'222111111',
        'X'=>'121121112',
        'Y'=>'221121111',
        'Z'=>'122121111',
        '-'=>'121111212',
        '.'=>'221111211',
        ' '=>'122111211',
        '$'=>'121212111',
        '/'=>'121211121',
        '+'=>'121112121',
        '%'=>'111212121',
        '*'=>'121121211',
    );

    private $unit = 'px'; // Unit
    private $bw = 3; // bar width

    private $height = 0; // px
    private $fs = 0; //Font size
    private $yt = 0;
    private $dx = 0;
    private $x = 0;
    private $y = 0;
    private $bl = 0;

    public function __construct() {

        $this->height = 50 * $this->bw;
        $this->fs = 8 * $this->bw;
        $this->yt = 45 * $this->bw;
        $this->dx = 3 * $this->bw;
        $this->x = 4 * $this->bw;
        $this->y = 2.5 * $this->bw;
        $this->bl = 35 * $this->bw;

    } 
    

    function checksum( $string ) {

        $checksum = 0;
        $length   = strlen( $string );
        $charset  = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ-. $/+%';
 
        for( $i=0; $i < $length; ++$i )
        {
            $checksum += strpos( $charset, $string[$i] );
        }
 
        return substr( $charset, ($checksum % 43), 1 );

    }


    function draw($str, $checksum=false) {
        
        // reset x-axis for next barcode when rendering multiple barcodes in same page
        if ($this->x > (4 * $this->bw)) {
            $this->x = 4 * $this->bw;
        }

        $str = strtoupper($str);
        if ($checksum) {
            $str = $str.$this->checksum($str);
        }
        $str = '*'.$str.'*';
        $long = (strlen($str) + 3) * 12;
        $width = $this->bw * $long;
        $text = str_split($str);
        $img = '';
        $img .= "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?>\n<!DOCTYPE svg PUBLIC \"-//W3C//DTD SVG 1.1//EN\" \"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd\">\n";
        $img .= "<svg width='$width$this->unit' height='$this->height$this->unit' version='1.1' xmlns='http://www.w3.org/2000/svg'>\n";
        
        foreach($text as $char){
            $img .= $this->drawsymbol($char);
        }
        $img .= '</svg>';

        return $img;

    }


    function drawsymbol($char) {
        
        $this->x += $this->bw;
        $img = '';
        $img .= '<desc>'.htmlspecialchars($char)."</desc>\n";
        $xt = $this->x + $this->dx;
        $img .= "<text x='$xt$this->unit' y='$this->yt$this->unit' font-family='Arial' font-size='$this->fs'>$char</text>\n";
        $val = str_split($this->Code39[$char]);
        $len = 9;

        for ($i=0; $i<$len; $i++){
            $num = (int) $val[$i];
            $w = $this->bw * $num;
            if(!($i % 2)){
                $img.= "<rect x='$this->x$this->unit' y='$this->y$this->unit' width='$w$this->unit' height='$this->bl$this->unit' fill='black' stroke-width='0' />\n";
            }
            $this->x += $w;
        }

        return $img;
    }

}

?>
