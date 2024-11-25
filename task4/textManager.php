<?php

//Класс работы с текстом
class TextManager
{
    public  $wordscount = 0;
    public $clearRest = false;
    public $wordsLimit = 0;

    // Заполнение аттрибутов html-тега
    private function MakeAttributes($node): string
    {
        $html = "";
        if ($node->hasAttributes()) {
            foreach ($node->attributes as $attr) {
                $html .= ' ' . $attr->nodeName . '="' . $attr->nodeValue . '"';
            }
            $html .= " ";
        }
        return $html;
    }

    //создание открывающего тега
    private function MakeOpenTag($node): string
    {
        $html = "<" . $node->nodeName;
        $html = $this->MakeAttributes($node);
        $html .= ">";
        return $html;
    }

    //создание закрывающего
    private function MakeCloseTag($node): string
    {
        $html = "";
        $html .= "</" . $node->nodeName . ">";
        return $html;
    }

    //Обаботка элемента DOM в случае если это текст
    private function HandleTextNode($node): string
    {
        if ($node->nodeType === XML_TEXT_NODE) {
            $wordsString = $node->nodeValue;
            $wordsString = trim(preg_replace('/\s+/', ' ', $wordsString));
            $words = explode(" ", string: $wordsString);
            
            //пропуск пустых элементов
            if (empty($wordsString)) {
                return $node->nodeValue;
            }

            $toPaste = " ";
            //Подсчет количества слов. Словом считается последовательность символовом окруженная пробелами внутри тега
            foreach ($words as $word) {
                if ($this->wordscount < $this->wordsLimit) {
                    $this->wordscount++;
                    $toPaste .= $word . " ";
                    continue;
                }

                //Если достигнуто нужное количетсво слов, остальной текст в теге удаляется
                $this->clearRest = true;
            }

            // Добавление многоточия в конец
            if ($this->clearRest) {
                $node->textContent = $toPaste . "...";
                return $node->nodeValue;
            }
            return $node->nodeValue;
        }
        return $node->nodeValue;
    }

    //Рекурсивный обход 
    private function processNodes($node)
    {
        // Если нужно удалить последующий текст последующих элементов, он становится равным пустой строке
        if ($this->clearRest == true) {
            $node->textContent = "";
        }

        $this->HandleTextNode($node);
        $html = $this->MakeOpenTag($node);

        foreach ($node->childNodes as $child) {
            $html .= $this->processNodes($child);
        }

        $html .= $this->MakeCloseTag($node);
        return $html;
    }

    // Главная функция 
    public function shortTag(string $html, string $tag, int $wordsLimit, int $tagNumber): string
    {
        if ($wordsLimit <= 0) {
            throw new InvalidArgumentException('Words limit must be positive');
        }

        $this->wordscount = 0;
        $this->clearRest = false;

        $this->wordsLimit = $wordsLimit;
        $text = mb_convert_encoding($html, "HTML-ENTITIES", "UTF-8");

        $dom = new DOMDocument();
        $dom->loadHTML($text);

        $node = $dom->getElementsByTagName($tag)->item($tagNumber);
        if (!$node) {
            throw new RuntimeException("Tag '$tag' with index $tagNumber not found");
        }

        $this->processNodes($node);

        $result = $dom->saveHTML();
        return $result;
    }
}
