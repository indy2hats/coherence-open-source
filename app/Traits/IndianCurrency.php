<?php

namespace App\Traits;

trait IndianCurrency
{
    private $amount;
    private $hasPaisa;
    private $rupees;
    private $paisa;

    public function get_words($amount)
    {
        if ($amount < 1) {
            return 'Zero';
        }
        $this->getAmount($amount);
        $w = '';
        $crore = (int) ($this->rupees / 10000000);
        $this->rupees = $this->rupees % 10000000;
        $w .= $this->single_word($crore, 'Crore ');
        $lakh = (int) ($this->rupees / 100000);
        $this->rupees = $this->rupees % 100000;
        if ($this->rupees == 0) {
            return  $w .= $this->single_word($lakh, 'Lakh ').' Rupees Only';
        }
        $w .= $this->single_word($lakh, 'Lakh ');
        $thousand = (int) ($this->rupees / 1000);
        if (($this->rupees % 1000) == 0) {
            $w .= ($lakh != 0) ? ' and ' : '';

            return  $w .= $this->single_word($thousand, 'Thousand ').' Rupees Only';
        }
        $this->rupees = $this->rupees % 1000;
        $w .= $this->single_word($thousand, 'Thousand ');
        $hundred = (int) ($this->rupees / 100);
        if (($this->rupees % 100) == 0) {
            return  $w .= ' and '.$this->single_word($hundred, 'Hundred ').' Rupees Only';
        }
        $w .= $this->single_word($hundred, 'Hundred ');
        if ($this->hasPaisa) {
            $ten = $this->rupees % 100;
            $w .= $this->single_word($ten, '');
            $w .= 'Rupees ';
            if ($this->paisa[0] == '0') {
                $this->paisa = (int) $this->paisa;
            } elseif (strlen($this->paisa) == 1) {
                $this->paisa = $this->paisa * 10;
            }
            $w .= ' and '.$this->single_word($this->paisa, ' Paisa');
        } else {
            $ten = $this->rupees % 100;
            if ($ten != 0) {
                $w .= ' and '.$this->single_word($ten, '');
            }
            $w .= 'Rupees ';
        }

        return $w.' Only';
    }

    public function getAmount($amount)
    {
        $this->amount = $amount;
        $this->hasPaisa = false;
        $arr = explode('.', $this->amount);
        $this->rupees = $arr[0];
        if (isset($arr[1]) && ((int) $arr[1]) > 0) {
            if (strlen($arr[1]) > 2) {
                $arr[1] = substr($arr[1], 0, 2);
            }
            $this->hasPaisa = true;
            $this->paisa = $arr[1];
        }
    }

    private function single_word($n, $txt)
    {
        $t = '';
        if ($n <= 19) {
            $t = $this->words_array($n);
        } else {
            $a = $n - ($n % 10);
            $b = $n % 10;
            $t = $this->words_array($a).' '.$this->words_array($b);
        }
        if ($n == 0) {
            $txt = '';
        }

        return $t.' '.$txt;
    }

    private function words_array($num)
    {
        $n = [0 => '', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine', 10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen', 16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen', 19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty', 40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty', 70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety', 100 => 'Hundred'];

        return $n[$num];
    }
}
