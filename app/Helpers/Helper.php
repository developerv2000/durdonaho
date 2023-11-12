<?php

/**
 * Custom Helper class
 * @author Bobur Nuridinov <bobnuridinov@gmail.com> 
 */

namespace App\Helpers;

use Image;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Helper
{
    /**
     * remove tags, replace many spaces by one, remove first whitespace
     * cut string if length givven
     * and return clean text
     * used while sharing in socials/messangers
     * 
     * @param string $string
     * @param integer $length
     * @return string
     */
    public static function cleanText($string, $length = null)
    {
        $cleaned = preg_replace('#<[^>]+>#', ' ', $string); //remove tags
        $cleaned = str_replace('&nbsp;', ' ', $cleaned); //decode space
        if ($length) {
            $cleaned = mb_strlen($cleaned) < $length ? $cleaned : mb_substr($cleaned, 0, ($length - 4)) . '...'; //cut length
        }
        $cleaned = preg_replace('!\s+!', ' ', $cleaned); //many spaces into one
        $cleaned = trim($cleaned); //remove whitespaces

        return $cleaned;
    }

    /**
     * remove tags, slice body, replace many spaces by one, remove first whitespace
     * and return clean text
     * used while sharing in socials/messangers
     * 
     * @param string $string
     * @return string
     */
    public static function generateShareText($string)
    {
        $cleaned = preg_replace('#<[^>]+>#', ' ', $string); //remove tags
        $cleaned = str_replace('&nbsp;', ' ', $cleaned); //decode space
        $cleaned = mb_strlen($cleaned) < 170 ? $cleaned : mb_substr($cleaned, 0, 166) . '...'; //cut length
        $cleaned = preg_replace('!\s+!', ' ', $cleaned); //many spaces into one
        $cleaned = trim($cleaned); //remove whitespaces

        return $cleaned;
    }

    /**
     * Generate unique slug for given model
     *
     * @param string $string Generates slug from this string
     * @param string $model Full namespace of model
     * @param integer $ignoreId ignore slug of a model with a given id (used while updating model)
     * @return string
     */
    public static function generateUniqueSlug($string, $model, $ignoreId = null)
    {
        $cyrilic = [
            'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п',
            'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я',
            'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П',
            'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', ' ',
            'ӣ', 'ӯ', 'ҳ', 'қ', 'ҷ', 'ғ', 'Ғ', 'Ӣ', 'Ӯ', 'Ҳ', 'Қ', 'Ҷ',
            '/', '\\', '|', '!', '?', '«', '»', '“', '”'
        ];

        $latin = [
            'a', 'b', 'v', 'g', 'd', 'e', 'io', 'zh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p',
            'r', 's', 't', 'u', 'f', 'h', 'ts', 'ch', 'sh', 'shb', 'a', 'i', 'y', 'e', 'yu', 'ya',
            'a', 'b', 'v', 'g', 'd', 'e', 'io', 'zh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p',
            'r', 's', 't', 'u', 'f', 'h', 'ts', 'ch', 'sh', 'shb', 'a', 'i', 'y', 'e', 'yu', 'ya', '-',
            'i', 'u', 'h', 'q', 'j', 'g', 'g', 'i', 'u', 'h', 'q', 'j',
            '', '', '', '', '', '', '', '', ''
        ];

        $transilation = str_replace($cyrilic, $latin, $string);
        $slug = strtolower($transilation);

        while ($model::where('slug', $slug)->where('id', '!=', $ignoreId)->first()) {
            $slug = $slug . '-1';
        }

        return $slug;
    }

    /**
     * Upload models file & update models column. Images can be resized after upload 
     * 
     * Resizing (Only Images) works only if width or height is given
     * Image will be croped from the center, If both width and height are given (fit)
     * Else if one of the parameters is given (width or height), another will be calculated auto (aspectRatio)
     *
     * @param \Http\Request $request
     * @param \Eloquent\Model\ $model
     * @param string $column Requested file input name and Models column name
     * @param string $name Name for newly creating file
     * @param string $path Path to store
     * @param integer $width Width for resize
     * @param integer $height Height for resize
     * 
     * @return void
     */
    public static function uploadModelsFile($request, $model, $column, $name, $path, $width = null, $height = null)
    {
        // Any file input maybe nullable on model update
        $file = $request->file($column);
        if ($file) {
            $filename = $name . '.' . $file->getClientOriginalExtension();
            $filename = Helper::renameIfFileAlreadyExists($path, $filename);

            $fullPath = public_path($path);

            $file->move($fullPath, $filename);
            $model[$column] = $filename;

            //resize image
            if ($width || $height) {
                $image = Image::make($fullPath . '/' . $filename);

                // fit
                if ($width && $height) {
                    $image->fit($width, $height, function ($constraint) {
                        $constraint->upsize();
                    }, 'center');

                // aspect ratio
                } else {
                    $image->resize($width, $height, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }

                $image->save($fullPath . '/' . $filename);
            }
        }
    }

    /**
     * Make thumb from original and store it in thumbs folder
     * Image will be croped from the center, If both width and height are given (fit)
     * Else if one of the parameters is given (width or height), another will be calculated auto (aspectRatio)
     * Thumbs will be saved in original-path/thumbs folder
     * 
     * Warning
     * To escape errors, thumbs folder must exists in original-path
     *
     * @param string $path Path of original image
     * @param string $filename Name of original image 
     * @param integer $width Width of thumb in pixels
     * @param integer $height Height of thumb in pixels
     * @return void
     */
    public static function createThumbs($path, $filename, $width = 400, $height = null)
    {
        $fullPath = public_path($path);
        $thumb = Image::make($fullPath . '/' . $filename);

        // fit
        if ($width && $height) {
            $thumb->fit($width, $height, function ($constraint) {
                $constraint->upsize();
            }, 'center');

        // aspect ration
        } else {
            $thumb->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        $thumb->save($fullPath . '/thumbs//' . $filename);

        return true;
    }

    /**
     * Fill Eloquent Model Items fields from request by loop. Used while storing & updating Eloquent Model item
     * 
     * @param \Http\Request $request
     * @param \Eloquent\Model $model
     * @param array $fields
     * @return void
     */
    public static function fillModelColumns($model, $fields, $request,)
    {
        foreach ($fields as $field) {
            $model[$field] = $request[$field];
        }
    }

    /**
     * Rename file if file with the given name is already exists on the given folder
     * Renaming type => oldName + (1)
     * 
     * @param string $path
     * @param string $filename
     * @return string
     */
    public static function renameIfFileAlreadyExists($path, $filename)
    {
        $name = pathinfo($filename, PATHINFO_FILENAME);
        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        $fullPath = public_path($path) . '/';

        while (file_exists($fullPath . $filename)) {
            $name = $name . '(1)';
            $filename = $name . '.' . $extension;
        }

        return $filename;
    }

    /**
     * Get all keywords in text and surround them by span with a highlighted class 
     */
    public static function highlightKeyword($keyword, $text)
    {
        return preg_replace("/" . $keyword . "/iu", "<span class='highlighted'>" . $keyword .  "</span>", $text);
    }

    /**
     * Get all keywords in text and surround them by span with a highlighted class 
     */
    public static function reverseOrderType($orderType)
    {
        return $orderType == 'asc' ? 'desc' : 'asc';
    }

    /**
     * Get previous route name
     *
     * @return string Route name
     */
    public static function getPreviousRouteName()
    {
        $previousRequest = app('request')->create(app('url')->previous());

        try {
            $routeName = app('router')->getRoutes()->match($previousRequest)->getName();
        } catch (NotFoundHttpException $exception) {
            return null;
        }

        return $routeName;
    }

    public function copyDB() {
        // copying Authors
        Jauthor::orderBy('id')->each(function ($old) {
            $new = new Author();
            $new->id = $old->id;
            $new->name = $old->name;
            $new->slug = Helper::generateUniqueSlug($new->name, Author::class);
            $new->user_id = 1;
            $new->biography = $old->biography;

            $extension = pathinfo($old->photo, PATHINFO_EXTENSION);
            $newImageName = $new->slug  . '.' . $extension;
            rename(public_path('img/jauthors/' . $old->photo), public_path('img/authors/' . $newImageName));
            $new->image = $newImageName;

            $new->approved = true;
            $new->popular = $old->popular;

            $new->save();
        });

        // copying Categories
        Bategory::orderBy('id')->each(function ($old) {
            $new = new Category();
            $new->id = $old->id;
            $new->title = $old->name;
            $new->approved = true;

            if($old->id == 39) {
                $new->popular = true;
                $new->image = 'buniodi_hasti.jpg';
            }
            
            if($old->id == 38) {
                $new->popular = true;
                $new->image = 'dastovardho_va_sahtgiri.jpg';
            }

            if($old->id == 41) {
                $new->popular = true;
                $new->image = 'olam_va_kosmologiya.jpg';
            }
            
            if($old->id == 37) {
                $new->popular = true;
                $new->image = 'sabki_zindagii_solim.jpg';
            }

            $new->save();
        });

        // copying quotes
        Vuote::orderBy('id')->each(function ($old) {
            $new = new Quote();
            $new->id = $old->id;
            $new->body = $old->body;
            $new->author_id = $old->author_id;
            $new->user_id = 1;
            $new->source_id = 3;
            $new->popular = $old->popular;
            $new->verified = true;
            $new->approved = true;

            $new->save();
        });


        // remove deleted relations
        DB::table('category_quote')->get()->each(function ($item) {
            $quote = Quote::find($item->quote_id);
            $category = Category::find($item->category_id);

            if(!$category) {
                DB::table('category_quote')->where('category_id', $item->category_id)->delete();
            }

            if(!$quote) {
                DB::table('category_quote')->where('quote_id', $item->quote_id)->delete();
            }
        });

    }
}
