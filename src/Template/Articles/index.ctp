<!-- File: src/Template/Articles/index.ctp -->
<h1>Articles</h1>
<?= $this->Html->link('Add Article', ['action' => 'add']) ?>
<table>
    <tr>
        <th>Title</th>
        <th>Created</th>
        <?php if(isset($authUser)): ?>
           <th>Action</th>
        <?php endif; ?>
    </tr>
    <!-- Here is where we iterate through our $articles query object, printing out
˓→article info -->
    <?php foreach ($articles as $article) : ?>
        <tr>
            <td>
                <?= $this->Html->link($article->title, ['action' => 'view', $article->slug]) ?>
            </td>
            <td>
                <?= $article->created->format(DATE_RFC850) ?>
            </td>
            <?php if(isset($authUser)): ?>
              <td>
                 <?= $this->Html->link('Edit', ['action' => 'edit', $article->slug]) ?>
                 <?= $this->Form->postLink('Delete', ['action' => 'delete', $article->slug], ['confirm' => 'Are you sure?']) ?>
              </td>
            <?php endif; ?>
        </tr>
    <?php endforeach; ?>
</table>
