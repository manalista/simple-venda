<?php 

class ViewHelper{
    
    public function getTags($content) {
        $erTag = "/[!#{}\/]/";
        $erNomeTag = "/#{\w*}/";
        $blocos = array();
        //Encontra as tags;
        preg_match_all($erNomeTag, $content, $blocos);
        $tagsFound = array();
        foreach ($blocos[0] as $tag) {
            array_push($tagsFound, preg_replace($erTag, "", $tag));
        }
        return $tagsFound;
    }

    public function getBloco($tag, $content) {
        $abertura = "#{" . $tag . "}";
        $fechamento = '#{/' . $tag . '}';
        $inicio = strpos($content, $abertura);
        $fim = strpos($content, $fechamento, $inicio);
        $inicioBloco = $inicio + strlen($abertura);
        $bloco = substr($content, $inicioBloco, $fim - $inicioBloco);
        return $bloco;
    }

    public function removeBloco($tag, $bloco, $content) {
        $r = str_replace('#{' . $tag . "}", "", $content);
        $r = str_replace('#{/' . $tag . "}", "", $r);
        $r = str_replace($bloco, "", $r);
        $content = $r;
        return $r;
    }

    public function removeTags($bloco) {
        $erTagAbertura = "/#{[\w]*}/";
        $erTagFechamento = "/#{\/[\w]*}/";
        $ers = array();
        $ers[0] = $erTagAbertura;
        $ers[1] = $erTagFechamento;
        return preg_replace($ers, "", $bloco);
    }

}