<?php
/**
 * Created by PhpStorm.
 * User: pol
 * Date: 2019-01-21
 * Time: 08:59
 */
require __DIR__ .'/../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class Mail
{
    private static $my_mail = 'pol.van.rijn@uol.de';

    static function send_confirmation($email, $url, $obj){
        $obj = DB::extract_obj_information($obj);
        $body =
            array(
                \Mail::ln('Geachte deelnemer,'),
                \Mail::ln(''),
                \Mail::ln('Klik op de onderstaande knop om de afspraak op ' . $obj['date'] . ' van ' . $obj['start'] . ' tot ' . $obj['end'] . ' te bevestigen:'),
                \Mail::ln(''),
                \Mail::print_action_button($url, 'Bevestig afspraak', 'btn-success'),
                \Mail::ln(''),
                \Mail::ln('Met vriendelijke groet,'),
                \Mail::ln('Pol van Rijn'),
            );

        $subject = 'Bevestigingsmail eyetracking onderzoek';

        new \Mail($email, $subject, $body);
    }



    static function send_invitation($obj){
        $params = array(
            'attachments' => array(realpath(__DIR__.'/../../src/algemene_informatie_deelnemers.pdf')),
        );
        $obj = DB::extract_obj_information($obj);
        $id = $obj['id'];
        $base_url = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}";;
        $body =
            array(
                \Mail::ln('Geachte deelnemer,'),
                \Mail::ln(''),
                \Mail::ln('Hiermee bevestigen we uw afspraak op ' . $obj['date'] . ' van ' . $obj['start'] . ' tot ' . $obj['end'] . '.'),
                \Mail::ln('Zorg ervoor dat u er een paar minuten eerder bent en dat u uw bril meeneemt als u een bril draagt.'),
                \Mail::ln('Lees ook van te voren de algemene informatie voor deelnemers (zie bijlage).'),
                \Mail::ln('Het onderzoek vindt plaats in de:'),
                \Mail::ln("<img src='$base_url/src/map.gif'>"),
                \Mail::ln(''),
                \Mail::ln('Uw persoonlijke code is'),
                \Mail::ln("<code>$id</code>"),
                \Mail::ln(''),
                \Mail::ln('Mocht er iets tussen komen of past een ander tijdstip toch beter, dan kunt u uw aanmelding wijzigen op:'),
                \Mail::print_action_button("$base_url/profile.php?id=$id", 'Aanmelding wijzigen', 'btn-danger'),
                \Mail::ln(''),
                \Mail::ln('Als u nog vragen heeft, kunt u graag contact met mij opnemen. Contactgegevens staan onderaan deze mail.'),

                \Mail::ln(''),
                \Mail::ln('Met vriendelijke groet,'),
                \Mail::ln('Pol van Rijn'),
            );

        $subject = "Uitnodiging eyetracking onderzoek {$obj['date']} {$obj['start']}-{$obj['end']}";

        new \Mail($obj['email'], $subject, $body, $params);

        $subject = "New participant: {$obj['date']} {$obj['start']}-{$obj['end']} ($id)";
        new \Mail(self::$my_mail, $subject, '');
    }

    static function send_cancel($obj){

        $obj = DB::extract_obj_information($obj);
        $id = $obj['id'];
        $base_url = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}";;
        $body =
            array(
                \Mail::ln('Geachte deelnemer,'),
                \Mail::ln(''),
                \Mail::ln('U heeft uw afspraak op ' . $obj['date'] . ' van ' . $obj['start'] . ' tot ' . $obj['end'] . ' online afgezegd. Bedankt voor uw interesse aan het onderzoek.'),
                \Mail::ln("U kunt zich elk moment weer hier aanmelden:"),
                \Mail::print_action_button("$base_url", 'Opnieuw aanmelden', 'btn-success'),

                \Mail::ln(''),
                \Mail::ln('Met vriendelijke groet,'),
                \Mail::ln('Pol van Rijn'),
            );

        $subject = "Afspraak eyetracking onderzoek afgezegd";

        new \Mail($obj['email'], $subject, $body);

        $subject = "Participant cancelled: {$obj['date']} {$obj['start']}-{$obj['end']} ($id)";
        new \Mail(self::$my_mail, $subject, '');
    }

    static function send_change($old_obj, $new_obj){
        $params = array(
            'attachments' => array(realpath(__DIR__.'/../../src/algemene_informatie_deelnemers.pdf')),
        );
        $obj = DB::extract_obj_information($old_obj);
        $old_date = $obj['date'];
        $old_time = $obj['start'] . " tot " . $obj['end'];
        $obj = DB::extract_obj_information($new_obj);

        $id = $obj['id'];
        $base_url = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}";;
        $body = array(
            \Mail::ln('Geachte deelnemer,'),
            \Mail::ln(''),
            \Mail::ln('Hiermee bevestigen we de wijziging van uw afspraak. De oude afspraak was op '. $old_date . ' van ' . $old_time  .'. De nieuwe afspraak is op <b>' . $obj['date'] . ' van ' . $obj['start'] . ' tot ' . $obj['end'] . '</b>.'),
            \Mail::ln('Zorg ervoor dat u er een paar minuten eerder bent en dat u uw bril meeneemt als u een bril draagt.'),
            \Mail::ln('Lees ook van te voren de algemene informatie voor deelnemers (zie bijlage).'),
            \Mail::ln('Het onderzoek vindt plaats in de:'),
            \Mail::ln("<img src='$base_url/src/map.gif'>"),
            \Mail::ln(''),
            \Mail::ln('Uw persoonlijke code is'),
            \Mail::ln("<code>$id</code>"),
            \Mail::ln(''),
            \Mail::ln('Mocht er iets tussen komen of past een ander tijdstip toch beter, dan kunt u uw aanmelding wijzigen op:'),
            \Mail::print_action_button("$base_url/profile.php?id=$id", 'Aanmelding wijzigen', 'btn-danger'),
            \Mail::ln(''),
            \Mail::ln('Als u nog vragen heeft, kunt u graag contact met mij opnemen.'),

            \Mail::ln(''),
            \Mail::ln('Met vriendelijke groet,'),
            \Mail::ln('Pol van Rijn'),
        );

        $subject = "Wijziging afspraak eyetracking onderzoek {$obj['date']} {$obj['start']}-{$obj['end']}";
        new \Mail($obj['email'], $subject, $body, $params);

        $subject = "Participant changed to: {$obj['date']} {$obj['start']}-{$obj['end']} ($id)";
        new \Mail(self::$my_mail, $subject, '');
    }

    static function send_reminder($obj){
        $obj = DB::extract_obj_information($obj);

        $id = $obj['id'];
        $base_url = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}";;
        $body = array(
            \Mail::ln('Geachte deelnemer,'),
            \Mail::ln(''),
            \Mail::ln('Hiermee herinneren we u aan uw afspraak vandaag van ' . $obj['start'] . ' tot ' . $obj['end'] . '.'),
            \Mail::ln('Zorg ervoor dat u er een paar minuten eerder bent en dat u uw bril meeneemt als u een bril draagt.'),
            \Mail::ln('Uw persoonlijke code is'),
            \Mail::ln("<code>$id</code>"),
            \Mail::ln(''),
            \Mail::ln('Mocht er iets tussen komen, dan kunt u uw aanmelding wijzigen op:'),
            \Mail::print_action_button("$base_url/profile.php?id=$id", 'Aanmelding wijzigen', 'btn-danger'),
            \Mail::ln(''),
            \Mail::ln(htmlentities('Na het onderzoek verzoeken wij u om een online enquete met achtergrondinformatie in te vullen. U kunt de enquete pas na het onderzoek invullen. Deze informatie is belangrijk voor het onderzoek.')),
            \Mail::print_action_button("$base_url/survey.php?id=$id", htmlentities('Enquete invullen'), 'btn-success'),
            \Mail::ln(''),
            \Mail::ln('Als u nog vragen heeft, kunt u graag contact met mij opnemen.'),

            \Mail::ln(''),
            \Mail::ln('Met vriendelijke groet,'),
            \Mail::ln('Pol van Rijn'),
        );

        $subject = "Herinnering afspraak eyetracking onderzoek vandaag {$obj['start']}-{$obj['end']}";
        new \Mail($obj['email'], $subject, $body);
    }

    static function send_schedule($times){

        $body = array(
            \Mail::ln('Hey Pol,'),
            \Mail::ln(''),
            \Mail::ln('You have the following participants today:'),
        );
        foreach($times as $obj){
            array_push($body, \Mail::ln('<b>' . $obj['id'] . '</b> ' . $obj['start'] . '-' . $obj['end']));
        }

        foreach (array('', 'Met vriendelijke groet,', 'Pol van Rijn') as $line){
            array_push($body, \Mail::ln($line));
        }

        $subject = "Today's testing schedule";
        new \Mail(self::$my_mail, $subject, $body);
    }

    static function send_reminder_survey($obj){
        $obj = DB::extract_obj_information($obj);

        $id = $obj['id'];
        $base_url = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}";;
        $body = array(
            \Mail::ln('Geachte deelnemer,'),
            \Mail::ln(''),
            \Mail::ln('Bedankt voor uw deelname aan het experiment op ' . $obj['date'] . '.'),
            \Mail::ln(htmlentities('Zou u de online enquete met achtergrondinformatie willen invullen? Dit is erg belangrijk voor het onderzoek.')),

            \Mail::print_action_button("$base_url/survey.php?id=$id", htmlentities('Enquete invullen'), 'btn-success'),

            \Mail::ln(''),
            \Mail::ln('Met vriendelijke groet,'),
            \Mail::ln('Pol van Rijn'),
        );

        $subject = htmlentities("Enquete achtergrondinformatie eyetracking onderzoek van {$obj['date']}");
        new \Mail($obj['email'], $subject, $body);
    }

    static function send_survey($id, $path_to_csv){
        $params = array(
            'attachments' => array(realpath($path_to_csv)),
        );

        $body = array(
            \Mail::ln('Hey Pol,'),
            \Mail::ln(''),
            \Mail::ln('You can find the survey data attached to this mail.'),

            \Mail::ln(''),
            \Mail::ln('All the best,'),
            \Mail::ln('Pol van Rijn'),
        );

        $subject = "Survey data " . $id;

        new \Mail(self::$my_mail, $subject, $body, $params);

        # Remove the file
        unlink(realpath($path_to_csv));
    }




    public function __construct($to, $subject, $body, $params = null)
    {
        $font_size = '18px';
        $btn_font_size = '20px';
        $text_color = '#495057';
        $background_color = '#ffffff';
        $highlight_color = '#97e9ff';

        if (isset($params['summary'])) {
            $summary = $params['summary'];
        } else {
            $summary = null;
        }



        if (isset($params['attachments'])){
            $attachments = $params['attachments'];
        } else{
            $attachments = null;
        }

        if (isset($params['home_url'])) {
            $home_url = $params['home_url'];
        } else {
            $home_url = '';
        }

        if (isset($params['template_url'])) {
            $template_url = $params['template_url'];
        } else {
            $template_url = '';
        }
        $css_url = $template_url . 'styles';

        $font_family = "'PT Sans', sans-serif";

        $mail = '<!doctype html>';
        $mail .= '<html>';
        $mail .= '<head>';
        $mail .= '<meta name="viewport" content="width=device-width" />';
        $mail .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';

        $mail .= "<title>$subject</title>";
        $mail .= "<style>
    @import url('https://fonts.googleapis.com/css?family=PT+Sans:400,700');


    img {
            border: none;
            -ms-interpolation-mode: bicubic;
            max-width: 100%;
            }
        body {
            background-color: $background_color;
            font-family: $font_family;
            -webkit-font-smoothing: antialiased;
            font-size: $font_size;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
            color: $text_color;
        }
        table {
            border-collapse: separate;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
            width: 100%;
            }
        table td {
            font-family: $font_family;
            font-size: $font_size;
            vertical-align: top;
        }
        /* -------------------------------------
            BODY & CONTAINER
        ------------------------------------- */
        .body {
            background-color: $background_color;
            font-size: $font_size;
            width: 100%;
        }
        /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
        .container {
            display: block;
            margin: 0 auto !important;
            /* makes it centered */
            max-width: 580px;
            padding: 10px;
            width: 580px;
        }
        /* This should also be a block element, so that it will fill 100% of the .container */
        .content {
            box-sizing: border-box;
            display: block;
            margin: 0 auto;
            max-width: 580px;
            padding: 10px;
        }
        /* -------------------------------------
            HEADER, FOOTER, MAIN
        ------------------------------------- */
        .main {
            background: $background_color;
            border-radius: 3px;
            width: 100%;
            border: 1px solid #ced4da;
        }
        .wrapper {
            box-sizing: border-box;
            padding: 20px;
        }
        .content-block {
            padding-bottom: 10px;
            padding-top: 10px;
        }
        .footer {
            clear: both;
            margin-top: 10px;
            text-align: center;
            width: 100%;
        }
        .footer td,
        .footer p,
        .footer span,
        .footer a {
            color: #999999;
            font-size: 12px;
            text-align: center;
        }
        /* -------------------------------------
            TYPOGRAPHY
        ------------------------------------- */
        h1,
        h2,
        h3,
        h4 {
            color: #000000;
            font-family: $font_family;
            font-size: $font_size;
            font-weight: 400;
            line-height: 1.4;
            margin: 0;
            margin-bottom: 30px;
        }
        h1 {
            font-size: 35px;
            font-weight: 300;
            text-align: center;
            text-transform: capitalize;
        }
        p,
        ul,
        ol {
            font-family: $font_family;
            font-size: $font_size;
            font-weight: normal;
            margin: 0;
            margin-bottom: 15px;
        }
        p li,
        ul li,
        ol li {
            list-style-position: inside;
            margin-left: 5px;
        }
        a {
            color: $highlight_color;
            text-decoration: underline;
        }
        /* -------------------------------------
            BUTTONS
        ------------------------------------- */
        .btn {
            box-sizing: border-box;
            width: 100%; }
        .btn > tbody > tr > td {
            padding-bottom: 15px; }
        .btn table {
            width: auto;
        }
        .btn table td {
            background-color: #ffffff;
            border-radius: 5px;
            text-align: center;
        }
        .btn a {
            background-color: #ffffff;
            border: 1px solid;
            border-color:$highlight_color;
            border-radius: 5px;
            box-sizing: border-box;
            color: $highlight_color;
            cursor: pointer;
            display: inline-block;
            font-size: $btn_font_size;
            font-weight: bold;
            margin: 0;
            padding: 12px 25px;
            text-decoration: none;
        }
        .btn-primary table td {
            background-color: $highlight_color;
        }
        .btn-primary a {
            background-color: $highlight_color;
            border-color: $highlight_color;
            color: #ffffff;
        }
        
        .btn-success table td {
            background-color: #28a745;
        }
        .btn-success a {
            background-color: #28a745;
            border-color: #28a745;
            color: #ffffff;
        }
        
        .btn-danger table td {
            background-color: #dc3545;
        }
        .btn-danger a {
            background-color: #dc3545;
            border-color: #dc3545;
            color: #ffffff;
        }
        /* -------------------------------------
            OTHER STYLES THAT MIGHT BE USEFUL
        ------------------------------------- */
        .last {
            margin-bottom: 0;
        }
        .first {
            margin-top: 0;
        }
        .align-center {
            text-align: center;
        }
        .align-right {
            text-align: right;
        }
        .align-left {
            text-align: left;
        }
        .clear {
            clear: both;
        }
        .mt0 {
            margin-top: 0;
        }
        .mb0 {
            margin-bottom: 0;
        }
        .preheader {
            color: transparent;
            display: none;
            height: 0;
            max-height: 0;
            max-width: 0;
            opacity: 0;
            overflow: hidden;
            mso-hide: all;
            visibility: hidden;
            width: 0;
        }
        .powered-by a {
            text-decoration: none;
        }
        hr {
            border: 0;
            border-bottom: 1px solid #ffffff;
            margin: 20px 0;
        }
        /* -------------------------------------
            RESPONSIVE AND MOBILE FRIENDLY STYLES
        ------------------------------------- */
        @media only screen and (max-width: 620px) {
            table[class=body] h1 {
                font-size: 28px !important;
                margin-bottom: 10px !important;
            }
            table[class=body] p,
            table[class=body] ul,
            table[class=body] ol,
            table[class=body] td,
            table[class=body] span,
            table[class=body] a {
                font-size: 16px !important;
            }
            table[class=body] .wrapper,
            table[class=body] .article {
                padding: 10px !important;
            }
            table[class=body] .content {
                padding: 0 !important;
            }
            table[class=body] .container {
                padding: 0 !important;
                width: 100% !important;
            }
            table[class=body] .main {
                border-left-width: 0 !important;
                border-radius: 0 !important;
                border-right-width: 0 !important;
            }
            table[class=body] .btn table {
                width: 100% !important;
            }
            table[class=body] .btn a {
                width: 100% !important;
            }
            table[class=body] .img-responsive {
                height: auto !important;
                max-width: 100% !important;
                width: auto !important;
            }
        }
        /* -------------------------------------
            PRESERVE THESE STYLES IN THE HEAD
        ------------------------------------- */
        @media all {
            .ExternalClass {
                width: 100%;
            }
            .ExternalClass,
            .ExternalClass p,
            .ExternalClass span,
            .ExternalClass font,
            .ExternalClass td,
            .ExternalClass div {
                line-height: 100%;
            }
            .apple-link a {
                color: inherit !important;
                font-family: inherit !important;
                font-size: inherit !important;
                font-weight: inherit !important;
                line-height: inherit !important;
                text-decoration: none !important;
            }
            .btn-primary table td:hover {
                background-color: #34495e !important;
            }
            .btn-primary a:hover {
                background-color: #34495e !important;
                border-color: #34495e !important;
            }
        }
    </style>";
        $mail .= '</head>';
        $mail .= '<body class="">';
        $mail .= '<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body">';
        $mail .= '<tr>';
        $mail .= '<td>&nbsp;</td>';
        $mail .= '<td class="container">';
        $mail .= '<div class="content">';
        if (!is_null($summary)) {
            echo "<span class='preheader'>$summary</span>";
        }

        $mail .= '<!-- START HEADER -->';
        $mail .= '<div class="header">';
        $mail .= '<table role="presentation" border="0" cellpadding="0" cellspacing="0">';
        $mail .= '<tr>';
        $mail .= '<td class="content-block align-center">';
        $mail .= '<img style="max-width: 250px" src="https://uol.de/typo3conf/ext/uol_facelift/Resources/Public/Assets/Images/Logo-UOL.svg"><br>';
        $mail .= '</td>';
        $mail .= '</tr>';
        $mail .= '</table>';
        $mail .= '</div>';
        $mail .= '<!-- END HEADER -->';

        $mail .= '<table role="presentation" class="main">';

        $mail .= '<!-- START MAIN CONTENT AREA -->';
        $mail .= '<tr>';
        $mail .= '<td class="wrapper">';
        $mail .= '<table role="presentation" border="0" cellpadding="0" cellspacing="0">';
        $mail .= '<tr>';
        $mail .= '<td>';
        foreach ($body as $i => $line) {
            $mail .= "$line";
        }

        $mail .= '</td>';
        $mail .= '</tr>';
        $mail .= '</table>';
        $mail .= '</td>';
        $mail .= '</tr>';

        $mail .= '<!-- END MAIN CONTENT AREA -->';
        $mail .= '</table>';

        $mail .= '<!-- START FOOTER -->';
        $mail .= '<div class="footer">';
        $mail .= '<table role="presentation" border="0" cellpadding="0" cellspacing="0">';
        $mail .= '<tr>';
        $mail .= '<td class="content-block">';
        $mail .= '<span class="apple-link">Pol van Rijn</span><br>';
        $mail .= '<span class="apple-link">Carl von Ossietzky Universiteit, Oldenburg (Duitsland)</span><br>';
        $mail .= '<span class="apple-link">Instituut voor Neerlandistiek</span><br>';
        $mail .= '<span class="apple-link"><a href="mailto:pol.van.rijn@uol.de">pol.van.rijn@uni-oldenburg.de</a> | +49 1577 0297872</span>';
        $mail .= '</td>';
        $mail .= '</tr>';
        $mail .= '</table>';
        $mail .= '</div>';
        $mail .= '<!-- END FOOTER -->';

        $mail .= '<!-- END CENTERED WHITE CONTAINER -->';
        $mail .= '</div>';
        $mail .= '</td>';
        $mail .= '<td>&nbsp;</td>';
        $mail .= '</tr>';
        $mail .= '</table>';
        $mail .= '</body>';
        $mail .= '</html>';

        #$body = implode('\n', $body);
        $body = $mail;
        try {
            $reply_name = 'Pol van Rijn';
            date_default_timezone_set('Europe/Amsterdam');
            $mail = new PHPMailer(true);                              // Passing `true` enables exceptions

            //Server settings
            $mail->isSMTP();                                      // Set mailer to use SMTP
            include_once('creditials.php');
            $settings = get_email_creditials();
            $mail->Host = $settings['Host'];  // Specify main and backup SMTP servers
            $mail->SMTPAuth = $settings['SMTPAuth'];                               // Enable SMTP authentication
            $mail->Username = $settings['Username'];                 // SMTP username
            $mail->Password = $settings['Password'];                           // SMTP password
            $mail->SMTPSecure = $settings['SMTPSecure'];                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = $settings['Port'];                                    // TCP port to connect to
            $mail->isHTML(true);
            $mail->setFrom($settings['Username'], $reply_name);
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->addAddress($to);               // Name is optional
            $mail->Subject = $subject;
            $mail->Body    = $body;

            if (isset($params['SMTPDebug'])){
                $mail->SMTPDebug = $params['SMTPDebug'];
            }

            if (!is_null($attachments)){
                foreach ($attachments as $attachment){
                    $mail->addAttachment($attachment);         // Add attachments
                }
            }


            $mail->send();
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    static function print_action_button($link, $text, $type){
        $out  = '<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="btn '. $type . '">';
        $out .= '<tbody>';
        $out .= '<tr>';
        $out .= '<td align="left">';
        $out .= '<table role="presentation" border="0" cellpadding="0" cellspacing="0">';
        $out .= '<tbody>';
        $out .= '<tr>';
        $out .= "<td> <a href='$link' target='_blank'>$text</a> </td>";
        $out .= '</tr>';
        $out .= '</tbody>';
        $out .= '</table>';
        $out .= '</td>';
        $out .= '</tr>';
        $out .= '</tbody>';
        $out .= '</table>';
        return $out;
    }

    static function ln($line){
        return "<p>$line</p>";
    }

}