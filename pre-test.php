<?php
include_once('inc/header.php');
if (isset($_POST['native_lang1'], $_POST['native_lang2'], $_POST['native_lang3'], $_POST['reading_disorder'])) {
    if ($_POST['reading_disorder'] == 'no' and $_POST['lazy_eye'] == 'no' and $_POST['native_lang1'] == 'NL' and ($_POST['native_lang2'] == '' or $_POST['native_lang2'] == 'NL')
        and ($_POST['native_lang3'] == '' or $_POST['native_lang3'] == 'NL')) {
        $_SESSION['suitable'] = 1;
        echo "<script>window.location.replace('/booking.php');</script>";
    } else {
        $_SESSION['suitable'] = 0;
        echo "<script>window.location.replace('/index.php');</script>";

    }
} else { ?>
    <form method="post">
        <h1 class="mt-5">Enquête</h1>
        <p class="lead">Om te kunnen beoordelen of u aan het experiment kan deelnemen, wordt u verzocht de onderstaande vragen in te vullen</p>
        <h5>Wat zijn uw moedertalen?</h5>
        <div class="form-row">
            <div class="col">
                <label for="native_lang1">Eerste moedertaal</label>
                <select class="form-control" name="native_lang1" id="native_lang1" required>
                    <?php
                    foreach (get_all_lang() as $key => $value) {
                        echo "<option value='$key'>$value</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col">
                <label for="native_lang2">Tweede moedertaal (als aanwezig)</label>
                <select class="form-control" name="native_lang2" id="native_lang2">
                    <?php
                    foreach (get_all_lang() as $key => $value) {
                        echo "<option value='$key'>$value</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col">
                <label for="native_lang3">Derde moedertaal (als aanwezig)</label>
                <select class="form-control" name="native_lang3" id="native_lang3">
                    <?php
                    foreach (get_all_lang() as $key => $value) {
                        echo "<option value='$key'>$value</option>";
                    }
                    ?>
                </select>

            </div>
        </div>
        <small class="form-text text-muted mb-3">Laat de andere velden leeg, mocht u maar één moedertaal spreken
        </small>

        <div class="form-row">

            <div class="col">
                <h5 class="form-check form-check-inline">Is bij u ooit lees- of schrijfstoornis gediagnostiseerd?</h5>
                <select class="form-control" id="reading_disorder" name="reading_disorder" required>
                    <option value=''>Kies een antwoord uit</option>
                    <option value='yes'>Ja</option>
                    <option value='no'>Nee</option>
                </select>
            </div>
            <div class="col">
                <h5 class="form-check form-check-inline">Bent u ooit voor een lui oog behandeld?</h5>
                <select class="form-control" id="lazy_eye" name="lazy_eye" required>
                    <option value=''>Kies een antwoord uit</option>
                    <option value='yes'>Ja</option>
                    <option value='no'>Nee</option>
                </select>
            </div>
        </div>
        <input type="hidden" name="survey">
        <button type="submit" class="btn btn-lg btn-success mt-4">Verstuur</button>
    </form>
    <?php
}
include_once('inc/footer.php');