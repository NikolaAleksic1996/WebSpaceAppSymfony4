<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
/*
 * nasledjujemo iz BaseFixture a ne iz Fixture*/
class CommentFixture extends BaseFixture implements DependentFixtureInterface
{
    protected function loadData(ObjectManager $manager)//postavljamo je na protected
    {
        $this->createMany(Comment::class ,100, function (Comment $comment){
            $comment->setContent(
                $this->faker->boolean ? $this->faker->paragraph:$this->faker->sentence(2,true)
            );

            $comment->setAuthorName($this->faker->name);
            $comment->setCreatedAt($this->faker->dateTime('-1 months', '-1 seconds'));
            $comment->setIsDeleted($this->faker->boolean(20));//20 od 100 da budu izbrisana
            //sada treba da povezemo ovaj fixture sa article fixture
            //jedna solucija preko entity manager pa repositori ali je prosto jadno

            /*druga je bolja jer u createMany ima addReference()
             * */
            //$comment->setArticle($this->getReference(Article::class.'_'.$this->faker->numberBetween(0,9)));//ovim biramo radom od 10 artikla
          //  $comment->setArticle($this->getReference(Article::class.'_0'));//sa '_0' biramo da se svi komentari vezu na jedan taj artikal
            //pa zatim ./bin/console doctrine:fixture:load
            //pa zatim php bin/console doctrine:query:sql 'SELECT * FROM comment'
            //koristicemo kreiranu funksiju za bribavljanje referenci fuxturesa

            //sada ce raditi samo za ovaj fixture a ne za druge nazive fixturesa, moramo da obezbedimo da se articalfixture ucivtava pre ovog fuxtursa
            //a to radimo tako sto implementiramo DependentFIxtureInterface

            $comment->setArticle($this->getRandomReference(Article::class));
        });//kreiramo sa faker 100 komentara
        $manager->flush();
    }

    function getDependencies()//ovo je ta methoda koja prvo vraca ArtFixture
    {
        return[
            ArticleFixtures::class,
        ];
    }
}
