<div id="carouselWelcome" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#carouselWelcome" data-slide-to="0" class="active"></li>
        <li data-target="#carouselWelcome" data-slide-to="1"></li>
        <li data-target="#carouselWelcome" data-slide-to="2"></li>
        <li data-target="#carouselWelcome" data-slide-to="3"></li>
        <li data-target="#carouselWelcome" data-slide-to="4"></li>
        <li data-target="#carouselWelcome" data-slide-to="5"></li>
        <li data-target="#carouselWelcome" data-slide-to="6"></li>
    </ol>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <?= $this->Html->image('carousel/carousel_1.jpg', [
                'class' => 'd-block w-100'
            ]) ?>
        </div>
        <div class="carousel-item">
            <?= $this->Html->image('carousel/carousel_2.jpg', [
                'class' => 'd-block w-100'
            ]) ?>
        </div>
        <div class="carousel-item">
            <?= $this->Html->image('carousel/carousel_3.jpg', [
                'class' => 'd-block',
                'style' => 'height:440px'
            ]) ?>
        </div>
        <div class="carousel-item">
            <?= $this->Html->image('carousel/carousel_4.jpg', [
                'class' => 'd-block w-100',
                'style' => 'height:440px'
            ]) ?>
        </div>
        <div class="carousel-item">
            <?= $this->Html->image('carousel/carousel_5.jpg', [
                'class' => 'd-block w-100'
            ]) ?>
        </div>
        <div class="carousel-item">
            <?= $this->Html->image('carousel/carousel_6.jpg', [
                'class' => 'd-block w-100'
            ]) ?>
        </div>
        <div class="carousel-item">
            <?= $this->Html->image('carousel/carousel_7.jpg', [
                'class' => 'd-block w-100',
                'style' => 'height:440px'
            ]) ?>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-target="#carouselWelcome" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-target="#carouselWelcome" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </button>
</div>