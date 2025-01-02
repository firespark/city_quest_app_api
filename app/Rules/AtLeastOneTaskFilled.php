namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AtLeastOneTaskFilled implements Rule
{
    public function passes($attribute, $value)
    {
        // Проверяем, что хотя бы одно текстовое поле или файл заполнено
        $texts = request()->input('task1_text', []);
        $files = request()->file('task1_image', []);

        $hasText = array_filter($texts, fn($text) => !empty(trim($text))); // Не пустые текстовые поля
        $hasFile = array_filter($files); // Загруженные файлы

        return !empty($hasText) || !empty($hasFile);
    }

    public function message()
    {
        return 'Вы должны заполнить хотя бы один Вопрос 1 или загрузить изображение.';
    }
}
