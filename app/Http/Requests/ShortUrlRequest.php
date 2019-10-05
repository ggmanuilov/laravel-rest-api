<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @summary Manage of short link resource
 *
 * @description
 *  This request mostly needed to specity flags
 *
 * @_204 Successful MF!
 */
class ShortUrlRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $case = $this->method();
        switch ($case) {
            case self::METHOD_GET:
                return [
                    'id' => 'required|integer',
                ];
                break;
            case self::METHOD_PUT:
                return [
                    'id'       => 'required|integer',
                    'long_url' => 'required|url|string|max:1024',
                ];
                break;
            case self::METHOD_POST:
                return [
                    'long_url' => 'required|url|string|max:1024',
                ];
                break;
            default:
                throw new \DomainException(sprintf('Invalid method: %s', $case));
                break;

        }
    }
}
