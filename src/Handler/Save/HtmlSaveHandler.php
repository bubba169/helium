<?php

namespace Helium\Handler\Save;

use Illuminate\Http\Request;
use Helium\Config\Form\Field\Field;
use Illuminate\Database\Eloquent\Model;

class HtmlSaveHandler
{
    /**
     * Saves the request data for the field to an entry. Runs HTML Purifier to
     * clean the HTML before saving
     */
    public function __invoke(Field $field, Request $request, Model $entry, array $path)
    {
        $entry->{$field->column} = $this->purifyHtml(
            $request->input($field->getDataPath($path))
        );
    }

    /**
     * Remove any nasties from the HTML
     *
     * @param string $html
     * @return string
     */
    public function purifyHtml(string $html) : string
    {
        $config = \HTMLPurifier_Config::create(config('helium.purifier.config'));
        $def = $config->getHTMLDefinition(true);

        foreach (config('helium.purifier.custom.attributes') as $element => $attributes) {
            foreach ($attributes as $attribute => $value) {
                $def->addAttribute('iframe', 'allowfullscreen', 'Bool');
            }
        }

        $purifier = new \HTMLPurifier($config);
        return $purifier->purify($html);
    }
}
