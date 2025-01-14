<div class="col-lg-9 col-md-8 offset-md-2">
    <div class="blog-container mt-5">
        <div class="blog-card" style="margin-bottom:50px; ">
            <div class="bg-nav text-white p-4 text-center rounded">
                <h2 class="text-white mt-5 pt-5"><?= $post->title ?></h2>
                <h5 class="text-white "><?= $post->category ?>, <?php
                  $dateString = $post->date_created;
                  $dateTime = new DateTime($dateString);
                  echo $dateTime->format("F jS,  Y g:i A");
                  ?></h5>
            </div>
            <div class="blog-fake-img rounded m-auto mt-3"><img src="<?= $post->img ?>" alt="" class="blog-img" style="width: calc(100% - 20px); height: calc(40% - 20px); object-fit: cover; object-position: center; padding: 15px;"></div>

            <pre class="text-justified rounded mt-5" style="word-wrap: break-word; white-space: pre-wrap;"><p><?= $post->body ?></p></pre>

            <p><span><a href="home">← Back to home</a></span></p>
        </div>

    </div>
</div>