<?php

namespace App\Orchid\Screens\Idea;

use App\Casts\PackageTypeEnum;
use App\Casts\PostTypeEnum;
use App\Models\IdeaKey;
use App\Models\IdeaRequest;
use App\Models\Package;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Components\Cells\Boolean;
use Orchid\Screen\Components\Cells\DateTimeSplit;
use Orchid\Screen\Components\Cells\Number;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Switcher;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Persona;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'ideaRequests' => IdeaRequest::with(['user', 'key'])
                ->filters()
                ->paginate(),
            'metrics' => [
                'unused_keys' =>  IdeaKey::where('activated',0)->count(),
                'used_keys' => IdeaKey::where('activated',1)->count()
            ]
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Запросы ключей';
    }

    /**
     * A description of the screen to be displayed in the header.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return '';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     * @throws \ReflectionException
     */
    public function layout(): iterable
    {
        return [

            Layout::metrics([
                'Выдано ключей:'              => 'metrics.used_keys',
                'Не использовано ключей:'    => 'metrics.unused_keys',

            ]),

            Layout::table('ideaRequests', [

                TD::make('Пользователь')
                    ->cantHide()
                    ->width(250)
                    ->render(fn(IdeaRequest $ideaRequest) => new Persona($ideaRequest->user->presenter())),

                /*
                TD::make('first_name','Имя')
                    ->width(150),
                TD::make('last_name','Фамилия')
                    ->width(150),

                TD::make('city','Город')
                    ->width(150),
                TD::make('email','email')
                    ->width(150),
                */


                TD::make('message','Сообщение')
                    ->alignLeft()
                    ->render(fn(IdeaRequest $ideaRequest) => Str::of($ideaRequest->message)->trim()->words(30))
                    ->width(300),



                TD::make('key', 'Статус')
                    ->width(100)
                    ->render(function (IdeaRequest $ideaRequest) {
                        if($ideaRequest->key()->exists()){
                            return  Blade::render('<x-icon path="bs.check" height="1.5em" width="1.5em" />');
                        }
                        return '-';
                    }),


                TD::make('created_at', __('Created'))
                    ->usingComponent(DateTimeSplit::class)
                    ->align(TD::ALIGN_RIGHT)
                    ->defaultHidden()
                    ->sort(),

                TD::make('updated_at', 'Последнее обновление')
                    ->usingComponent(DateTimeSplit::class)
                    ->defaultHidden()
                    ->align(TD::ALIGN_RIGHT)
                    ->sort(),

                TD::make(__('Actions'))
                    ->align(TD::ALIGN_CENTER)
                    ->width('100px')
                    ->render(fn(IdeaRequest $ideaRequest) => Link::make('Просмотр')
                        ->route('platform.idea.request', $ideaRequest->id)
                        ->icon('bs.pencil')),
            ]),

        ];
    }
}
