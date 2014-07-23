<h2>Letter/Create</h2>

<div class="index">
<div><a href="#" id="fill-fake-data">Fill fake data.</a></div>
<?php
echo $this->Form->create('Letter');
echo $this->Form->input('category_id', array('type' => 'select', 'options' => Configure::read('Letter.categories'), 'label' => 'category'));
echo $this->Form->input('cheer_id', array('type' => 'select', 'options' => Configure::read('Letter.cheer_ids'), 'label' => 'cheer'));
echo $this->Form->input('title', array('type' => 'text'));
echo $this->Form->input('text', array('type' => 'textarea'));
?>
<fieldset>
<legend>Link</legend>
<?
echo $this->Form->input('link_title', array('type' => 'text'));
echo $this->Form->input('link_url', array('type' => 'text'));
?>
</fieldset>
<fieldset>
<legend>BgMusic</legend>
<?
echo $this->Form->input('bgmusic_url', array('type' => 'text'));
?>
</fieldset>
<fieldset>
<legend>Extra</legend>
<?php
echo $this->Form->input('letter_type', array('type' => 'radio', 'options' => Configure::read('Letter.letter_types'), 'class' => 'letter_type'));
?>
<div id="form-book" class="form-extra">
<div><a href="#" id="fill-fake-book-data">Fill fake book data.</a></div>
<?php
echo $this->Form->input('book_id', array('type' => 'text'));
echo $this->Form->input('book_title', array('type' => 'text'));
echo $this->Form->input('book_author', array('type' => 'text'));
echo $this->Form->input('book_cover_url', array('type' => 'text'));
echo $this->Form->input('book_quote', array('type' => 'textarea'));
echo $this->Form->input('book_text', array('type' => 'textarea'));
?>
</div>
<div id="form-video" class="form-extra">
<div><a href="#" id="fill-fake-video-data">Fill fake video data.</a></div>
<?php
echo $this->Form->input('video_type', array('type' => 'text'));
echo $this->Form->input('video_url', array('type' => 'text'));
?>
</div>
<div id="form-voice" class="form-extra">
<div><a href="#" id="fill-fake-voice-data">Fill fake voice data.</a></div>
<?php
echo $this->Form->input('voice_url', array('type' => 'text'));
?>
</div>
<div id="form-photo" class="form-extra">
<div><a href="#" id="fill-fake-photo-data">Fill fake photo data.</a></div>
<?php
echo $this->Form->input('photo_url', array('type' => 'text'));
echo $this->Form->input('photo_path', array('type' => 'text'));
echo $this->Form->input('photo_width', array('type' => 'text'));
echo $this->Form->input('photo_height', array('type' => 'text'));
?>
</div>
</fieldset>
<?php 
echo $this->Form->submit();
echo $this->Form->end();
?>
</div>

<div class="actions">
<ul>
<li><?= $this->Html->link('Index', array('action' => 'index')) ?></li>
</ul>
</div>

<script>
$(document).ready(function() {
    $('.letter_type').click(function() {
        var checked = $('input[class=letter_type]:checked').val();

        $('.form-extra').hide();
        $('.form-extra input, .form-extra textarea').each(function() {
            $(this).attr('disabled', true);
        });

        $('#form-' + checked).show();
        $('input, textarea', $('#form-' + checked)).each(function() {
            $(this).attr('disabled', false);
        });
    });

    $('#fill-fake-data').click(function() {
        $.each(["LetterTitle", "LetterText", "LetterLinkTitle"], function(i, v) {
            $('#' + v).val(Faker.Lorem.sentence());
        });
        $.each(["LetterLinkUrl", "LetterBgmusicUrl"], function(i, v) {
            $('#' + v).val('http://' + Faker.Internet.domainName() + '/' + (Faker.Lorem.words()).join('/'));
        });
        $.each(["LetterCategoryId", "LetterCheerId"], function(i, v) {
            $('#' + v).val(1 + Faker.random.number(2));
        });

        return false;
    });

    $('#fill-fake-book-data').click(function() {
        $('#LetterBookId').val(Faker.random.number(10000));
        $('#LetterBookCoverUrl').val(Faker.Image.imageUrl());
        $('#LetterBookAuthor').val([Faker.Name.firstName(), Faker.Name.lastName()].join(' '));

        $.each(["LetterBookTitle", "LetterBookQuote", "LetterBookText"], function(i, v) {
            $('#' + v).val(Faker.Lorem.sentence());
        });

        return false;
    });

    $('#fill-fake-video-data').click(function() {
        $('#LetterVideoUrl').val('http://' + ['youtube.com', 'vimeo.com'][Faker.random.number(1)] + '/' + (Faker.Lorem.words()).join('/'));
        $('#LetterVideoType').val(['youtube', 'vimeo'][Faker.random.number(1)])

        return false;
    });

    $('#fill-fake-voice-data').click(function() {
        $.each(["LetterVoiceUrl"], function(i, v) {
            $('#' + v).val('http://' + Faker.Internet.domainName() + '/' + (Faker.Lorem.words()).join('/'));
        });

        return false;
    });

    $('#fill-fake-photo-data').click(function() {
        $('#LetterPhotoUrl').val(Faker.Image.imageUrl());
        $('#LetterPhotoPath').val('/' + (Faker.Lorem.words()).join('/'));
        $.each(['LetterPhotoWidth', 'LetterPhotoHeight'], function(i, v) {
            $('#' + v).val(1000 +Faker.random.number(1000));
        });

        return false;
    });

    $('.form-extra').hide();
});
</script>
