
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
	    <p class="navbar-text navbar-right">
		<a href="#" class="navbar-link">ほげ</a> 
	    </p>
	</div>
    </div>
</div>
