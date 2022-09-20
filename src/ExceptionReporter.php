<?php


namespace mohamadmurad\LaravelTelegramReporter;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use mohamadmurad\LaravelTelegramReporter\Bot\Telegram;

/**
 * This is the exception handler class.
 *
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class ExceptionReporter
{


    public function notify($e, $request)
    {
        $userId = Auth::check() ? auth()->user()->id : null;
        $dateTime = date('Y-m-d H:i:s') . PHP_EOL . PHP_EOL;

        $text = 'Report From *' . config('app.name') . '*' . PHP_EOL;
        $text .= 'URL :  *' . $request->url() . '*' . PHP_EOL;
        $text .= 'Error ' . PHP_EOL;
        $text.= PHP_EOL .'Date : ' . $dateTime;
        if ($userId) {
            $text .= 'User : ' . $userId . PHP_EOL;
        }

        $text .= 'Error Message :  *' . $e->getMessage()  .'*'  . PHP_EOL;
        $text .= 'Error Code :  *' . $e->getCode() .'*'. PHP_EOL .PHP_EOL;

        $text .= 'Request Header :  ' .PHP_EOL . json_encode($request->header()) . PHP_EOL .PHP_EOL;
        $text .= 'Request Parameters :  ' . PHP_EOL. json_encode($request->all()) . PHP_EOL .PHP_EOL;




        $text = str_replace('{','{'. PHP_EOL . ' ' ,$text);
        $text = str_replace('}',PHP_EOL . '}'  ,$text);
        $text = str_replace(',',  ',' . PHP_EOL. ' '  ,$text);

        try {

            $telegram = new Telegram(config('telegram-report.token'));
            $response = $telegram->sendMessage([
                'chat_id' => config('telegram-report.chat_id'),
                'text' => $text,
                'parse_mode' => 'Markdown',
            ]);


        } catch (\Exception $e) {
            echo $e->getMessage();
        }

    }


}
