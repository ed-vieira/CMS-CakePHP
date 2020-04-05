
<h1><?= h($article->title) ?></h1>
<p><?= h($article->body) ?></p>
<p><b>Tags:</b> <?= h($article->tag_string) ?></p>
<p><small>Created: <?= $article->created->format(DATE_RFC850) ?></small></p>


<?php if(isset($authUser)): ?>
    <p><?= $this->Html->link('Edit', ['action' => 'edit', $article->slug]) ?></p>
<?php endif; ?>


