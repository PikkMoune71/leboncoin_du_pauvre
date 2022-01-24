<?php

namespace App\Factory;

use App\Entity\Article;
use App\Entity\Tags;
use App\Repository\ArticleRepository;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Article>
 *
 * @method static Article|Proxy createOne(array $attributes = [])
 * @method static Article[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Article|Proxy find(object|array|mixed $criteria)
 * @method static Article|Proxy findOrCreate(array $attributes)
 * @method static Article|Proxy first(string $sortedField = 'id')
 * @method static Article|Proxy last(string $sortedField = 'id')
 * @method static Article|Proxy random(array $attributes = [])
 * @method static Article|Proxy randomOrCreate(array $attributes = [])
 * @method static Article[]|Proxy[] all()
 * @method static Article[]|Proxy[] findBy(array $attributes)
 * @method static Article[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Article[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static ArticleRepository|RepositoryProxy repository()
 * @method Article|Proxy create(array|callable $attributes = [])
 */
final class ArticleFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            // TODO add your default values here (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories)
            'name' => self::faker()->realText(30),
            'slug' => self::faker()->slug(),
            'image' => self::faker()->imageUrl(),
            'subtitle' => self::faker()->text(),
            'description' => self::faker()->text(),
            'price' => self::faker()->randomFloat(2, 1, 999),
            'published_at' => self::faker()->dateTimeBetween('-100 days', '-1 second'),
            'tags' => TagsFactory::random(),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this->afterInstantiate(function(Article $article): void {
            $slugger = new AsciiSlugger();
            $article->setSlug($slugger->slug($article->getName()));
        })
        ;
    }

    protected static function getClass(): string
    {
        return Article::class;
    }
}
