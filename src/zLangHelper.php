<?php

namespace App\Traits;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Lang;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Mateo Cerna
 *
 * This Trait is used as a Helper to make the translations easier between Laravel default translations and
 * Vue, it contains a set of functions made as a toolbox for you to make the translations easier.
 */
class LangHelper
{

    /**
     * localeHandler
     * @string $locale = The language code you want to change the language to
     *
     * This function gets the locale you pass and compares it with the available languages, if its not one of them
     * it gets the last correct language and uses it.
     */
    public static function localeHandler(?string $locale = null): void {
        if (!in_array($locale, ['es', 'en', 'ca'])) {
            $locale = Session::get('locale', App::getLocale());
        }

        App::setLocale($locale);
        Session::put('locale', $locale);
    }

    /**
     * getTranslation
     * @string $filename = the name of the file with the wanted translations
     *
     * return array
     *
     * This function uses the object lang to get the translations from the designated file
     */
    public static function getTranslation(string $filename): array {
        return Lang::get($filename);
    }

    /** 
     * 
     * getMultipleTranslations
     * @string[] $files = the names of multiple translations files
     *
     * return array
     *
     * This function gets all the translations from different files
     */
    public static function getMultipleTranslations(array $files): array {
        $translations = [];
        foreach ($files as $file) {
            $translations = array_merge($translations, Lang::get($file));
        }
        return $translations;
    }

    /**
     * change
     * @string $locale = the two letters that specifies the language
     */
    public static function change(): Response {
        $locale = request('locale', App::getLocale());
        if (!in_array($locale, ['es', 'en', 'ca'])) {
            $locale = Session::get('locale', App::getLocale());
        }
        App::setLocale($locale);
        Session::put('locale', $locale);

        if (substr(request('currentUrl'), 1) == "") {
            $route = "home";
        } else {
            $route = substr(request('currentUrl'), 1);
        }
        
        return Inertia::location(route($route));
    }
}