<?php 

namespace App\Presenters;

use Laracasts\Presenter\Presenter;

class PostPresenter extends Presenter {

    public function readTime()
    {
        $totalWords = str_word_count($this->article);
        $minutesToRead = round($totalWords / 200);

        return (int)max(1, $minutesToRead) . ' menit';
    }

}