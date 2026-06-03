<?php
require __DIR__ . '/../path.php';
include __DIR__ . '/../app/controllers/cert.php";
$topics = selectAll('topics');
$menuTree = buildTree($topics);

if(!$_SESSION){
    header ('location:' . BASE_URL . '/account/signin.php');
}
?>
<!DOCTYPE html>
<html>

<head>
    <?php include("../temp/admin_head.php");?>
</head>

<body>

<div class="page">
    <div class="container__block">

        <!-------------------------header------------------------>
        <?php include("../temp/admin_header.php");?>
        <!-------------------------header end------------------------>

        <!-------------------------sidebar------------------------>
        <?php include("../temp/admin_sidebar.php");?>
        <!-------------------------sidebar end------------------------>

        <!-------------------------main----------------------------->
        <main class="main">
            <div class="container">
                <div class="post">
                <a class="btn--red btn--rounded btn" href="<?php echo BASE_URL . "admin/post/created.php";?>">Создать</a>
                    <div class="post__content">
                        <h1>Редактирование записи</h1>
                        <div class="err">

                        </div>
                        <table class="content__table">
                            <div class="add-post">
                                <form  class="add-post__form" action="edit.php" method="post" enctype="multipart/form-data">
                                    <input name="id" value="<?=$id;?>" type="hidden">
                                    <div class="form__group">

                                        <input name="title" value="<?=$post['title'];?>" type="text" placeholder="Название статьи" class="form__control form__control--md">
                                        <span class="form__line-profile">   </span>
                                    </div>
                                    <div class="form__group">
                                       <input name="anons" value="<?=$post['anons'];?>" type="text" placeholder="Краткое описание" class="form__control form__control--md">
                                       <span class="form__line-profile">   </span>
                                   </div>
                                    <div class="form__group">
                                        <textarea id="editor" class="add-post__textarea ck ck-editor__editable_inline" name="content" placeholder="Содержимое записи"></textarea>
                                   </div>

                                    <div class="form__button">
                                        <select class="form__select form__select--size" name="topic" aria-label="default select example" id="">

                                            <?php renderSelectOptions($menuTree); ?>
                                        </select>

                                        <div class="checkbox">
                                            <?php if (empty($publist) && $publish == 0): ?>
                                            <label class="checkbox__check" for="flexCheckChecked">Опубликовать
                                                <input name="publish" type="checkbox" id="checked" >
                                            </label>
                                            <?php else: ?>
                                                <label class="checkbox__check" for="flexCheckChecked">Опубликовать
                                                    <input name="publish" type="checkbox" id="checked">
                                                </label>
                                            <?php endif;?>
                                        </div>

                                        <div class="add-post__form-actions">
                                            <label class="add-post__file" for="add-post-file">
                                                <input name="img" type="file" id="add-post-file">
                                            </label>
                                        </div>
                                        <button class="btn btn--blue btn--rounded btn--save" name="post-edit" type="submit">Сохранить</button>
                                    </div>

                                </form>
                            </div>
                    </div>
<!--                    <div class="post__footer">-->
<!--                        <ul class="post__data">-->
<!--                            <li class="post__data-item">-->
<!--                                <time datetime="2022-11-10 10:02">10.11.2022</time>-->
<!---->
<!--                            </li>-->
<!--                        </ul>-->
<!--                    </div>-->

                </div>
            </div>
        </main>


        <!-------------------------main end----------------------------->
    </div>

</div>


<script src="/var/www/vhosts/akt-1shcool.edu.kz/httpdocs/assets/js/ad_editor.js"></script>

<script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/classic/ckeditor.js"></script>

<script>
            // This sample still does not showcase all CKEditor&nbsp;5 features (!)
            // Visit https://ckeditor.com/docs/ckeditor5/latest/features/index.html to browse all the features.
            CKEDITOR.ClassicEditor.create(document.getElementById("editor"), {
                // https://ckeditor.com/docs/ckeditor5/latest/features/toolbar/toolbar.html#extended-toolbar-configuration-format
                toolbar: {
                    items: [
                        'exportPDF','exportWord', '|',
                        'findAndReplace', 'selectAll', '|',
                        'heading', '|',
                        'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript', 'superscript', 'removeFormat', '|',
                        'bulletedList', 'numberedList', 'todoList', '|',
                        'outdent', 'indent', '|',
                        'undo', 'redo',
                        '-',
                        'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
                        'alignment', '|',
                        'link', 'insertImage', 'blockQuote', 'insertTable', 'mediaEmbed', 'codeBlock', 'htmlEmbed', '|',
                        'specialCharacters', 'horizontalLine', 'pageBreak'
        
                    ],
                    shouldNotGroupWhenFull: true
                },
                // Changing the language of the interface requires loading the language file using the <script> tag.
                // language: 'es',
                list: {
                    properties: {
                        styles: true,
                        startIndex: true,
                        reversed: true
                    }
                },
                // https://ckeditor.com/docs/ckeditor5/latest/features/headings.html#configuration
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                        { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                        { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                        { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
                        { model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' },
                        { model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6' }
                    ]
                },
                // https://ckeditor.com/docs/ckeditor5/latest/features/editor-placeholder.html#using-the-editor-configuration
                placeholder: 'Содержимое записи',
                // https://ckeditor.com/docs/ckeditor5/latest/features/font.html#configuring-the-font-family-feature
                fontFamily: {
                    options: [
                        'default',
                        'Arial, Helvetica, sans-serif',
                        'Courier New, Courier, monospace',
                        'Georgia, serif',
                        'Lucida Sans Unicode, Lucida Grande, sans-serif',
                        'Tahoma, Geneva, sans-serif',
                        'Times New Roman, Times, serif',
                        'Trebuchet MS, Helvetica, sans-serif',
                        'Verdana, Geneva, sans-serif'
                    ],
                    supportAllValues: true
                },
                // https://ckeditor.com/docs/ckeditor5/latest/features/font.html#configuring-the-font-size-feature
                fontSize: {
                    options: [ 10, 12, 14, 'default', 18, 20, 22 ],
                    supportAllValues: true
                },
                // Be careful with the setting below. It instructs CKEditor to accept ALL HTML markup.
                // https://ckeditor.com/docs/ckeditor5/latest/features/general-html-support.html#enabling-all-html-features
                htmlSupport: {
                    allow: [
                        {
                            name: /.*/,
                            attributes: true,
                            classes: true,
                            styles: true
                        }
                    ]
                },
                // Be careful with enabling previews
                // https://ckeditor.com/docs/ckeditor5/latest/features/html-embed.html#content-previews
                htmlEmbed: {
                    showPreviews: true
                },
                // https://ckeditor.com/docs/ckeditor5/latest/features/link.html#custom-link-attributes-decorators
                link: {
                    decorators: {
                        addTargetToExternalLinks: true,
                        defaultProtocol: 'https://',
                        toggleDownloadable: {
                            mode: 'manual',
                            label: 'Downloadable',
                            attributes: {
                                download: 'file'
                            }
                        }
                    }
                },
                // https://ckeditor.com/docs/ckeditor5/latest/features/mentions.html#configuration
                mention: {
                    feeds: [
                        {
                            marker: '@',
                            feed: [
                                '@apple', '@bears', '@brownie', '@cake', '@cake', '@candy', '@canes', '@chocolate', '@cookie', '@cotton', '@cream',
                                '@cupcake', '@danish', '@donut', '@dragée', '@fruitcake', '@gingerbread', '@gummi', '@ice', '@jelly-o',
                                '@liquorice', '@macaroon', '@marzipan', '@oat', '@pie', '@plum', '@pudding', '@sesame', '@snaps', '@soufflé',
                                '@sugar', '@sweet', '@topping', '@wafer'
                            ],
                            minimumCharacters: 1
                        }
                    ]
                },
                // The "super-build" contains more premium features that require additional configuration, disable them below.
                // Do not turn them on unless you read the documentation and know how to configure them and setup the editor.
                removePlugins: [
                    // These two are commercial, but you can try them out without registering to a trial.
                    // 'ExportPdf',
                    // 'ExportWord',
                    'AIAssistant',
                    'CKBox',
                    'CKFinder',
                    'EasyImage',
                    // This sample uses the Base64UploadAdapter to handle image uploads as it requires no configuration.
                    // https://ckeditor.com/docs/ckeditor5/latest/features/images/image-upload/base64-upload-adapter.html
                    // Storing images as Base64 is usually a very bad idea.
                    // Replace it on production website with other solutions:
                    // https://ckeditor.com/docs/ckeditor5/latest/features/images/image-upload/image-upload.html
                    // 'Base64UploadAdapter',
                    'RealTimeCollaborativeComments',
                    'RealTimeCollaborativeTrackChanges',
                    'RealTimeCollaborativeRevisionHistory',
                    'PresenceList',
                    'Comments',
                    'TrackChanges',
                    'TrackChangesData',
                    'RevisionHistory',
                    'Pagination',
                    'WProofreader',
                    // Careful, with the Mathtype plugin CKEditor will not load when loading this sample
                    // from a local file system (file://) - load this site via HTTP server if you enable MathType.
                    'MathType',
                    // The following features are part of the Productivity Pack and require additional license.
                    'SlashCommand',
                    'Template',
                    'DocumentOutline',
                    'FormatPainter',
                    'TableOfContents',
                    'PasteFromOfficeEnhanced'
                ]
            });
        </script>
</body>

</html>

