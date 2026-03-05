namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AtLeastOneTaskFilled implements Rule
{
    public function passes($attribute, $value)
    {
        $texts = request()->input('task1_text', []);
        $files = request()->file('task1_image', []);

        $hasText = array_filter($texts, fn($text) => !empty(trim($text)));
        $hasFile = array_filter($files);

        return !empty($hasText) || !empty($hasFile);
    }

    public function message()
    {
        return 'Вы должны заполнить хотя бы один Вопрос 1 или загрузить изображение.';
    }
}
