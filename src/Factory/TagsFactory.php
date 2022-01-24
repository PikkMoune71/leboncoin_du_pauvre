<?php

namespace App\Factory;

use App\Entity\Tags;
use App\Repository\TagsRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Tags>
 *
 * @method static Tags|Proxy createOne(array $attributes = [])
 * @method static Tags[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Tags|Proxy find(object|array|mixed $criteria)
 * @method static Tags|Proxy findOrCreate(array $attributes)
 * @method static Tags|Proxy first(string $sortedField = 'id')
 * @method static Tags|Proxy last(string $sortedField = 'id')
 * @method static Tags|Proxy random(array $attributes = [])
 * @method static Tags|Proxy randomOrCreate(array $attributes = [])
 * @method static Tags[]|Proxy[] all()
 * @method static Tags[]|Proxy[] findBy(array $attributes)
 * @method static Tags[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Tags[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static TagsRepository|RepositoryProxy repository()
 * @method Tags|Proxy create(array|callable $attributes = [])
 */
final class TagsFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    protected function getDefaults(): array
    {
        $tags = ["Vehicule", "Manteaux", "Electronique", "Bonnets", "Gaming"];
        return [
            // TODO add your default values here (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories)
            'name' => $tags[array_rand($tags, 1)],
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Tags $tags): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Tags::class;
    }
}
