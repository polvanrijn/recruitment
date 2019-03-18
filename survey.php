<?php
/**
 * Created by PhpStorm.
 * User: pol
 * Date: 2019-01-20
 * Time: 21:43
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once('inc/classes/DB.php');
include_once('inc/classes/Mail.php');


include_once('inc/header.php');
if (isset($_GET['id'])) {
    if (isset($_POST['action'])) {
        $list = array(
            array('field', 'value'),
        );

        unset($_POST['action']);
        $_POST['id'] = $_GET['id'];
        foreach ($_POST as $key => $value) {
            array_push($list, array($key, $value));
        }

        $file_path = __DIR__ . '/src/' . $_GET['id'] . '.csv';
        $fp = fopen($file_path, 'w');

        foreach ($list as $fields) {
            fputcsv($fp, $fields);
        }

        fclose($fp);
        \Mail::send_survey($_GET['id'], $file_path);
        echo "<script>window.location.replace('/index.php?msg=Bedankt voor uw deelname!');</script>";

    }

    $result = DB::get_slot($_GET['id']);
    $time = strtotime($result['date'] . ' ' . $result['end']);
    if (time() < $time) {
        echo '<h1 class="mt-5">U kunt de enquête pas na het experiment invullen</h1>';
    } elseif (!is_null($result['completed_survey'])) {
        echo '<h1 class="mt-5">U heeft de enquête al ingevuld, bedankt voor uw hulp!</h1>';
    } else {
        var_dump($_POST);
        ?>
        <form method="post">
            <h1 class="mt-5">Enquête</h1>
            <p class="lead">Vul a.u.b. de volgende gegevens in</p>
            <div class="row">
                <div class="col">
                    <label for="age">Leeftijd</label>
                    <input type="number" id="age" name="age" min="0" max="100" class="form-control"
                           placeholder="Leeftijd"
                           required>
                </div>
                <div class="col">
                    <label for="sex">Geslacht</label>
                    <br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="sex" id="male" value="male" required>
                        <label class="form-check-label" for="male">Man</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="sex" id="female" value="female" required>
                        <label class="form-check-label" for="female">Vrouw</label>
                    </div>
                </div>
                <div class="col">
                    <label for="still_studying">Studeert u nog?</label>
                    <br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="still_studying" value="yes" required>
                        <label class="form-check-label" for="yes">Ja</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="still_studying" value="no" required>
                        <label class="form-check-label" for="no">Nee</label>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col">
                    <label for="field_of_study">Studievak</label>
                    <input type="text" id="field_of_study" name="field_of_study" class="form-control"
                           placeholder="Studievak">
                </div>
                <div class="col">
                    <label for="highest_education">Hoogste opleidingsniveau</label>
                    <select class="form-control" name="highest_education" id="highest_education" required>
                        <option value="">Kies uit ...</option>
                        <option value='PhD'>Promotie</option>
                        <option value='master'>Master</option>
                        <option value='bachelor'>Bachelor</option>
                        <option value='VWO'>VWO-Eindexamen</option>
                        <option value='HAVO'>Havo-Eindexamen</option>
                        <option value='VMBO'>VMBO-Eindexamen</option>
                        <option value='no_formal_education'>geen eindexamen</option>
                    </select>
                </div>
                <div class="col">
                    <label for="formal_education">Aantal jaren formele opleiding</label>
                    <input type="number" id="formal_education" name="formal_education" min="0" max="100"
                           class="form-control" placeholder="Formale opleiding" required>
                    <small id="formal_education_Help" class="form-text text-muted">Basisschool t/m max. promotie</small>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col">
                    <label for="native_languages">Moedertaal/moedertalen</label>
                    <input type="text" id="native_languages" name="native_languages" class="form-control"
                           placeholder="Moedertalen" required>
                </div>
                <div class="col">
                    <label for="num_languages">Hoeveel talen sprak u voor uw 12e levensjaar?</label>
                    <input type="number" id="num_languages" name="num_languages" class="form-control" required>
                </div>
                <div class="col">
                    <label for="native_languages_free_text">Welke talen? En vanaf welke leeftijd begon u deze taal te
                        spreken?</label>
                    <textarea class="form-control" name="native_languages_free_text"
                              id="native_languages_free_text"></textarea>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col">
                    <label for="lenses">Draagt u contactlenzen?</label>
                    <br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="lenses" value="yes" required>
                        <label class="form-check-label" for="yes">Ja</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="lenses" value="no" required>
                        <label class="form-check-label" for="no">Nee</label>
                    </div>
                </div>
                <div class="col">
                    <label for="glasses">Draagt u een bril?</label>
                    <br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="glasses" value="yes" required>
                        <label class="form-check-label" for="yes">Ja</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="glasses" value="no" required>
                        <label class="form-check-label" for="no">Nee</label>
                    </div>
                </div>
                <div class="col">
                    <label for="right">Sterkte rechteroog</label>
                    <input type="number" id="right" name="right" class="form-control">
                </div>
                <div class="col">
                    <label for="left">Sterkte linkeroog</label>
                    <input type="number" id="left" name="left" class="form-control">
                </div>
            </div>

            <div class="row mt-3">
                <div class="col">
                    <label for="reading_disorder">Is bij u ooit lees- of schrijfstoring gediagnostiseerd? </label>
                    <br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="reading_disorder" value="yes" required>
                        <label class="form-check-label" for="yes">Ja</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="reading_disorder" value="no" required>
                        <label class="form-check-label" for="no">Nee</label>
                    </div>
                </div>
                <div class="col">
                    <label for="which_reading_disorder">Zo ja, welke?</label>
                    <input type="text" id="which_reading_disorder" name="which_reading_disorder" class="form-control">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col">
                    <?php echo create_options('Hoe belangrijk vindt u spelling?', 'importance_spelling'); ?>
                </div>
                <div class="col">
                    <?php echo create_options('Hoe belangrijk vindt u hoofdletters bij het schrijven?', 'importance_capitalization_writing'); ?>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-6">
                    <?php echo create_options('Hoe belangrijk vindt u hoofdletters bij het lezen?', 'importance_capitalization_reading'); ?>
                </div>

                <div class="col">
                    <label for="used_strategy">Heeft u tijdens het experiment een strategie toegepast? </label>
                    <br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="used_strategy" value="yes" required>
                        <label class="form-check-label" for="yes">Ja</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="used_strategy" value="no" required>
                        <label class="form-check-label" for="no">Nee</label>
                    </div>
                </div>
                <div class="col">
                    <label for="strategy">Beschrijf de strategie</label>
                    <textarea class="form-control" name="strategy" id="strategy"></textarea>
                </div>
            </div>
            <style>
                th {
                    font-weight: normal;
                }

                .table thead th {
                    border-bottom: none;
                }

                .table td, .table th {
                    position: relative;
                }

                td input {
                    left: 50%;
                }

            </style>
            <?php
            $options_x = array(
                1 => 'dagelijks',
                2 => 'meermaals per week',
                3 => 'minimaal 1x  per week',
                4 => 'minder'
            );
            $options_y = array(
                'chat_sms' => 'Chat-berichten en SMS',
                'letters' => 'Brieven en ansichtkaarten',
                'private_email' => 'Privé-e-mails',
                'work_email' => 'E-mails voor mijn werk',
                'reading_for_fun' => 'Teksten voor eigen plezier (romans, kranten, tijdschriften)',
                'academic' => 'Lesboeken, wetenschappelijke teksten',
                'else' => 'Andere'
            );


            echo create_matrix('Hoe vaak leest u teksten uit de volgende media?', 'reading_frequency_', $options_x, $options_y);

            $options_x = array(
                1 => 'erg graag',
                2 => 'graag',
                3 => 'niet zo graag',
                4 => 'niet graag'
            );
            echo create_matrix('Hoe graag leest u de volgende media?', 'reading_fun_', $options_x, $options_y);


            $options_x = array(
                1 => 'erg belangrijk',
                2 => 'belangrijk',
                3 => 'onbelangrijk',
                4 => 'erg onbelangrijk'
            );
            echo create_matrix('Hoe belangrijk vindt u spelling in...', 'importance_spelling_', $options_x, $options_y);

            ?>

            <div class="row">
                <div class="col">
                    <label for="comments">Andere op- en aanmerkingen</label>
                    <textarea class="form-control" name="comments" id="comments"></textarea>
                </div>
            </div>
            <input type="hidden" name="action" value="survey">
            <button type="submit" class="btn btn-lg btn-success mt-4 mb-5">Verstuur enquête</button>

        </form>
        <?php
    }
} else {
    echo '<h1 class="my-5">Ongeldige aanvraag</h1>';
}

function create_matrix($question, $abbreviation, $options_x, $options_y)
{
    $out = '<table class="table mt-5">';
    $out .= '<thead>';
    $out .= '<tr>';
    $out .= "<td scope='col'><b>$question</b></td>";
    foreach (array_values($options_x) as $header) {
        $out .= "<th scope='col'>$header</th>";
    }
    $out .= '</tr>';
    $out .= '</thead>';
    $out .= '<tbody>';
    foreach ($options_y as $key => $value) {
        $out .= '<tr>';
        $out .= "<th id='$key' scope='row'>$value</th>";
        foreach (array_keys($options_x) as $option) {
            $out .= "<td><input class='form-check-input' type='radio' name='$abbreviation$key' value='$option' required></td>";
        }
        $out .= '</tr>';
    }

    $out .= '</tbody>';
    $out .= '</table>';
    return $out;

}

function create_options($label, $name, $help_text = '<span class="float-left">1 = erg onbelangrijk</span> <span class="float-right">10 = erg belangrijk</span>')
{
    $out = "<div class='row'>";
    $out .= "<div class='col'>";
    $out .= "<p>$label</p>";
    $out .= '</div>';
    $out .= '</div>';
    $out .= "<div class='row'>";
    $out .= "<div class='col'>";
    for ($i = 1; $i <= 10; $i++) {
        $out .= '<div class="form-check form-check-inline">';
        $out .= "<input class='form-check-input' type='radio' name='$name' value='$i'>";
        $out .= "<label class='form-check-label'>$i</label>";
        $out .= '</div>';
    }
    $out .= "<small class='form-text text-muted'>$help_text</small>";

    $out .= '</div>';
    $out .= '</div>';
    return $out;

}

include_once('inc/footer.php');