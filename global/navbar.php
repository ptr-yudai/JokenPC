<div class="navbar navbar-default">
    <div class="container-fluid">
	<div class="navbar-header"><a class="navbar-brand" href="/">JPC</a></div>
	<div class="collapse navbar-collapse">
	    <ul class="nav navbar-nav">
		<?php print($jpc->navbar_active === 1
						  ? "<li class=\"active\">"
						  : "<li>"); ?><a href="/">ホーム</a></li>
		
		<?php print($jpc->navbar_active === 2
						  ? "<li class=\"active\">"
						  : "<li>"); ?><a href="/problem/">問題一覧</a></li>
		
		<?php print($jpc->navbar_active === 3
						  ? "<li class=\"active\">"
						  : "<li>"); ?><a href="/status/">解答状況</a></li>
	    </ul>
	    <div class="nav navbar-right">
		<div class="dropdown" style="padding-top: 8px;">
		    <?php if ($jpc->auth->is_logged_in()) { ?>
			<button class="btn btn-default dropdown-toggle" type="button" id="dropdownAboutMe" data-toggle="dropdown">
			    <?php print($jpc->h($_SESSION['username'])); ?>
			    <span class="caret"></span>
			</button>
			<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownAboutMe">
			    <li role="presentation"><a role="menuitem" tabindex="-1" href="/user/">個人設定</a></li>
			    <li role="presentation" class="divider"></li>
			    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">ログアウト</a></li>
			</ul>
		    <?php } else { ?>
			<a href="/login/" class="navbar-link">ログイン</a>
		    <?php } ?>
		</div>
	    </div>
	</div>
    </div>
</div>
