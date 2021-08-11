<div class="container">
    <div class="panel-group">
        <h3 class="panel-title">Group #1</h3>
        <div class="panel-body">
            111111111111
        </div>
        <h3 class="panel-title">Group #2</h3>
        <div class="panel-body">
            222222222222
        </div>
        <h3 class="panel-title">Group #3</h3>
        <div class="panel-body">
            333333333333
        </div>
    </div>
</div>

<?php
$js = <<< JS
var panelItem = document.querySelectorAll('.panel-title'),
active = document.getElementsByClassName('panel-active');

Array.from(panelItem).forEach(function(item, i, panelItem) {
item.addEventListener('click', function(e) {
    if (active.length > 0 && active[0] !== this)
        active[0].classList.remove('panel-active');
        this.classList.toggle('panel-active');
    });
});
JS;

$css = <<< CSS
.container {
max-width: 700px;
margin: 0 auto;
padding-top: 20px;
}
.panel {
margin-bottom: 5px;
}
.panel-title {
padding: 10px 15px;
background-color: #f5f5f5;
border: 1px solid #ddd;
font-size: 16px;
color: #333;
border-radius: 4px;
cursor: pointer;
margin-bottom: 5px;
}
.panel-body {
padding: 15px;
border: 1px solid #ddd;
border-bottom-left-radius: 4px;
border-bottom-right-radius: 4px;
display: none;
margin-bottom: 10px;
background: #fff;
}
.panel-active {
margin-bottom: 0;
border-bottom: 0;
border-bottom-left-radius: 0;
border-bottom-right-radius: 0;
text-decoration: underline;
}
.panel-active + .panel-body {
display: block;
}
CSS;
