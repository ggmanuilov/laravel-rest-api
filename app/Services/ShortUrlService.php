<?php


namespace App\Services;


use App\ShortUrls;
use Illuminate\Support\Facades\Validator;

class ShortUrlService
{
    const METHOD_GET = 'get';
    const METHOD_PUT = 'put';
    const METHOD_POST = 'post';
    const METHOD_DELETE = 'delete';

    protected $chars = "abcdfghjkmnpqrstvwxyz|ABCDFGHJKLMNPQRSTVWXYZ|0123456789";

    public function validate($case, $data)
    {
        $rules = $this->getRules($case);
        $validator = Validator::make($data, $rules);

        $validator->validate();
    }

    private function getRules($case)
    {
        switch ($case) {
            case self::METHOD_DELETE:
            case self::METHOD_GET:
                return [
                    'id' => 'required|integer',
                ];
                break;
            case self::METHOD_PUT:
                return [
                    'id' => 'required|integer',
                    'long_url' => 'required|string|max:1024',
                ];
                break;
            case self::METHOD_POST:
                return [
                    'long_url' => 'required|string|max:1024',
                ];
                break;
            default:
                throw new \DomainException(sprintf('Invalid method: %s', $case));
                break;

        }
    }

    /**
     * Converts an id to an encoded string.
     *
     * @param int $id Number to encode
     * @return string Encoded string
     */
    public function encode($id)
    {
        return self::convertIdToString($id, $this->chars);
    }

    /**
     * Converts a number to an alpha-numeric string.
     *
     * @param int $num Number to convert
     * @param string $s String of characters for conversion
     * @return string Alpha-numeric string
     */
    public static function convertIdToString($id, $s)
    {
        $b = strlen($s);
        $m = $id % $b;
        if ($id - $m == 0) return substr($s, $id, 1);
        $a = '';
        while ($m > 0 || $id > 0) {
            $a = substr($s, $m, 1) . $a;
            $id = ($id - $m) / $b;
            $m = $id % $b;
        }
        return $a;
    }

    /**
     * Check long url
     * @param string $longUrl
     * @return bool
     */
    public function getByLongUrl(string $longUrl)
    {
        $shortUrl = ShortUrls::where('long_url', $longUrl)->select(['short_url'])->first();
        return empty($shortUrl) ? false : $shortUrl;
    }

    /**
     * Check long url
     * @param string $longUrl
     * @return bool
     */
    public function getByShortUrl(string $longUrl)
    {
        $shortUrl = ShortUrls::where('short_url', $longUrl)->select(['id', 'short_url', 'long_url'])->first();
        return empty($longUrl) ? false : $shortUrl;
    }

    /**
     * Find by id
     * @param string $id
     * @return bool
     */
    public function getById(int $id)
    {
        $shortUrl = ShortUrls::where('id', $id)->select(['id', 'short_url', 'long_url'])->first();
        return empty($id) ? false : $shortUrl;
    }

    /**
     * Remove entity
     * @param ShortUrls $shortUrl
     * @return bool|null
     * @throws \Exception
     */
    public function delete(ShortUrls $shortUrl)
    {
        return $shortUrl->delete();
    }

    /**
     * Make new entity
     * @param string $longUrl
     * @return mixed|string
     */
    public function make(string $longUrl)
    {
        $shortUrl = new ShortUrls();
        $shortUrl->long_url = $longUrl;
        $shortUrl->save();

        $shortUrl->short_url = $this->encode($shortUrl->id);
        $shortUrl->save();
        return $shortUrl->only(['id', 'short_url']);
    }
}