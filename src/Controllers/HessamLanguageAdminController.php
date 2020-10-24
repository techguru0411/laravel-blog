<?php


namespace WebDevEtc\BlogEtc\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use WebDevEtc\BlogEtc\Helpers;
use WebDevEtc\BlogEtc\Middleware\UserCanManageBlogPosts;
use WebDevEtc\BlogEtc\Models\HessamLanguage;

class HessamLanguageAdminController extends Controller
{
    /**
     * HessamLanguageAdminController constructor.
     */
    public function __construct()
    {
        $this->middleware(UserCanManageBlogPosts::class);
    }

    public function index(){
        $language_list = HessamLanguage::all();
        return view("blogetc_admin::languages.index",[
            'language_list' => $language_list
        ]);
    }

    public function create_language(){
        return view("blogetc_admin::languages.add_language");
    }

    public function store_language(Request $request){
        $language = new HessamLanguage();
        $language->active = $request['active'];
        $language->iso_code = $request['iso_code'];
        $language->locale = $request['locale'];
        $language->name = $request['name'];
        $language->date_format = $request['date_format'];

        $language->save();

        Helpers::flash_message("Language: " . $language->name . " has been added.");
        return redirect( route('blogetc.admin.languages.index') );
    }

    public function destroy_language(Request $request, $languageId){
        try {
            $language = HessamLanguage::findOrFail($languageId);
            //todo
//        event(new CategoryWillBeDeleted($category));
            $language->delete();
            Helpers::flash_message("The language is successfully deleted!");
            return redirect( route('blogetc.admin.languages.index') );
        } catch (\Illuminate\Database\QueryException $e) {
            Helpers::flash_message("You can not delete this language, because it's used in posts or categoies.");
            return redirect( route('blogetc.admin.languages.index') );
        }
    }

    public function toggle_language(Request $request, $languageId){
        $language = HessamLanguage::findOrFail($languageId);
        if ($language->active == 1){
            $language->active = 0;
        }else if ($language->active == 0){
            $language->active = 1;
        }

        $language->save();
        //todo
        //event

        Helpers::flash_message("Language: " . $language->name . " has been disabled.");
        return redirect( route('blogetc.admin.languages.index') );
    }
}
