<?php


namespace BoShop\Tools;


use BoShop\System\Mutation;
use BoShop\System\Project;

class ProjectTools
{

    private static Project $project;
    private static Mutation $mutation;

    public static function getRunningProject(): Project {
        if(self::$project ?? null) {
            return self::$project;
        }

        $project = new Project();
        $project->getByDomain($_SERVER["HTTP_HOST"]);
        self::$project = $project;

        return $project;
    }

    public static function getProjectId(): int {
        return self::getRunningProject()->getPrimaryKeyValue();
    }

    public static function getMutation(): Mutation {
        if(self::$mutation ?? null) {
            return self::$mutation;
        }

        $language = substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2);
        $mutation = new Mutation();
        $mutation->getByLanguageAlias($language);

        self::$mutation = $mutation;

        return $mutation;
    }

    public static function getMutationId(): int {
        return self::getMutation()->getPrimaryKeyValue();
    }
}