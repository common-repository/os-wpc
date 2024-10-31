<?php
if(class_exists('OswpcAdmin')){
?>
	<div id="os-wpc">
	<?php include_once(OSWPC_INCLUDE_FILES."/admin-head.php"); ?>
		<div class="oswpc-wrap">
			<h2>はじめに</h2>
			<div class="oswpc-contents">
				<p>OS-WPカスタマイズプラグインを導入していただき、ありがとうございます。</p>
				<p>当プラグインを利用開始する前に、必ず<a href="?page=os-wpc-admin.php&amp;mode=agreement">利用規約</a>をご覧ください。</p>
				<p>ご連絡は<a href="http://lp.olivesystem.jp/plugin-oswpc-mail" title="問い合わせ" target="_blank">問い合わせフォーム</a>からお願い致します。</p>
			</div>
			<h2>最低動作環境</h2>
			<div class="oswpc-contents">
				WordPress3.1以上、MySQL4.1以上、JQuery1.7以上、ユーザのブラウザでJQueryが動作すること。
			</div>
			<h2>今後の予定</h2>
			<div class="oswpc-contents">
				<ul>
					<li>・公開側で、リダイレクト機能</li>
					<li>・ログイン画面（ロゴ等）の変更機能</li>
				</ul>
			</div>
			<h2>更新履歴</h2>
			<div class="oswpc-contents">
				<p>2017.04.07 WordPressバージョン4.7.3にて動作確認</p>
				<p>2016.08.05 独自ログインフォーム機能を追加。アクセス制限機能の不具合を修正。</p>
				<p>2016.05.04 メタタグgeneratorやフィードでのバージョン情報を非表示、管理画面内でのWordPressバージョン情報を表示・非表示にできる機能を追加。バージョンアップ通知を非表示にできる機能を追加。管理画面フッターのWP文章の変更機能。</p>
				<p>2016.04.30 その他のカスタマイズ。記事の自動改行解除、アイキャッチ機能有効、HTML5マークアップ有効、タイトルタグの自動出力の有効、RSSフィードのリンク有効、ウィジェット内ショートコード有効、ナビゲーションメニューを有効にできる機能</p>
				<p>2016.04.29 ユーザ別、権限グループ別によるページ表示制限機能</p>
				<p>2016.04.28 プラグインの設定機能</p>
				<p>2016.04.27 権限グループへの権限の追加・編集機能</p>
				<p>2016.04.22 軽微な修正</p>
				<p>2016.04.22 リリース</p>
			</div>
		</div>
	<?php include_once(OSWPC_INCLUDE_FILES."/admin-foot.php"); ?>
	</div>

<?php
}
?>