<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Like;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class PostController extends Controller
{
    public function getDashboard()
    {
        $posts = Post::orderBy('created_at', 'desc')->get();
        return view('dashboard', ['posts' => $posts]);
    }

    public function postCreatePost(Request $request)
    {
        $this->validate($request, [
            'body' => 'required|max:1000'
        ]);
        $post = new Post();
        $post->body = $request['body'];
        $message = 'There was an error';

        $arr = ['chuj','chuja', 'chujek', 'chuju', 'chujem', 'chujnia',
            'chujowy', 'chujowa', 'chujowe', 'cipa', 'cipę', 'cipe', 'cipą',
            'cipie', 'dojebać','dojebac', 'dojebie', 'dojebał', 'dojebal',
            'dojebała', 'dojebala', 'dojebałem', 'dojebalem', 'dojebałam',
            'dojebalam', 'dojebię', 'dojebie', 'dopieprzać', 'dopieprzac',
            'dopierdalać', 'dopierdalac', 'dopierdala', 'dopierdalał',
            'dopierdalal', 'dopierdalała', 'dopierdalala', 'dopierdoli',
            'dopierdolił', 'dopierdolil', 'dopierdolę', 'dopierdole', 'dopierdoli',
            'dopierdalający', 'dopierdalajacy', 'dopierdolić', 'dopierdolic',
            'dupa', 'dupie', 'dupą', 'dupcia', 'dupeczka', 'dupy', 'dupe', 'huj',
            'hujek', 'hujnia', 'huja', 'huje', 'hujem', 'huju', 'jebać', 'jebac',
            'jebał', 'jebal', 'jebie', 'jebią', 'jebia', 'jebak', 'jebaka', 'jebal',
            'jebał', 'jebany', 'jebane', 'jebanka', 'jebanko', 'jebankiem',
            'jebanymi', 'jebana', 'jebanym', 'jebanej', 'jebaną', 'jebana',
            'jebani', 'jebanych', 'jebanymi', 'jebcie', 'jebiący', 'jebiacy',
            'jebiąca', 'jebiaca', 'jebiącego', 'jebiacego', 'jebiącej', 'jebiacej',
            'jebia', 'jebią', 'jebie', 'jebię', 'jebliwy', 'jebnąć', 'jebnac',
            'jebnąc', 'jebnać', 'jebnął', 'jebnal', 'jebną', 'jebna', 'jebnęła',
            'jebnela', 'jebnie', 'jebnij', 'jebut', 'koorwa', 'kórwa', 'kurestwo',
            'kurew', 'kurewski', 'kurewska', 'kurewskiej', 'kurewską', 'kurewska',
            'kurewsko', 'kurewstwo', 'kurwa', 'kurwaa', 'kurwami', 'kurwą', 'kurwe',
            'kurwę', 'kurwie', 'kurwiska', 'kurwo', 'kurwy', 'kurwach', 'kurwami',
            'kurewski', 'kurwiarz', 'kurwiący', 'kurwica', 'kurwić', 'kurwic',
            'kurwidołek', 'kurwik', 'kurwiki', 'kurwiszcze', 'kurwiszon',
            'kurwiszona', 'kurwiszonem', 'kurwiszony', 'kutas', 'kutasa', 'kutasie',
            'kutasem', 'kutasy', 'kutasów', 'kutasow', 'kutasach', 'kutasami',
            'matkojebca', 'matkojebcy', 'matkojebcą', 'matkojebca', 'matkojebcami',
            'matkojebcach', 'nabarłożyć', 'najebać', 'najebac', 'najebał',
            'najebal', 'najebała', 'najebala', 'najebane', 'najebany', 'najebaną',
            'najebana', 'najebie', 'najebią', 'najebia', 'naopierdalać',
            'naopierdalac', 'naopierdalał', 'naopierdalal', 'naopierdalała',
            'naopierdalala', 'naopierdalała', 'napierdalać', 'napierdalac',
            'napierdalający', 'napierdalajacy', 'napierdolić', 'napierdolic',
            'nawpierdalać', 'nawpierdalac', 'nawpierdalał', 'nawpierdalal',
            'nawpierdalała', 'nawpierdalala', 'obsrywać', 'obsrywac', 'obsrywający',
            'obsrywajacy', 'odpieprzać', 'odpieprzac', 'odpieprzy', 'odpieprzył',
            'odpieprzyl', 'odpieprzyła', 'odpieprzyla', 'odpierdalać',
            'odpierdalac', 'odpierdol', 'odpierdolił', 'odpierdolil',
            'odpierdoliła', 'odpierdolila', 'odpierdoli', 'odpierdalający',
            'odpierdalajacy', 'odpierdalająca', 'odpierdalajaca', 'odpierdolić',
            'odpierdolic', 'odpierdoli', 'odpierdolił', 'opieprzający',
            'opierdalać', 'opierdalac', 'opierdala', 'opierdalający',
            'opierdalajacy', 'opierdol', 'opierdolić', 'opierdolic', 'opierdoli',
            'opierdolą', 'opierdola', 'piczka', 'pieprznięty', 'pieprzniety',
            'pieprzony', 'pierdel', 'pierdlu', 'pierdolą', 'pierdola', 'pierdolący',
            'pierdolacy', 'pierdoląca', 'pierdolaca', 'pierdol', 'pierdole',
            'pierdolenie', 'pierdoleniem', 'pierdoleniu', 'pierdolę', 'pierdolec',
            'pierdola', 'pierdolą', 'pierdolić', 'pierdolicie', 'pierdolic',
            'pierdolił', 'pierdolil', 'pierdoliła', 'pierdolila', 'pierdoli',
            'pierdolnięty', 'pierdolniety', 'pierdolisz', 'pierdolnąć',
            'pierdolnac', 'pierdolnął', 'pierdolnal', 'pierdolnęła', 'pierdolnela',
            'pierdolnie', 'pierdolnięty', 'pierdolnij', 'pierdolnik', 'pierdolona',
            'pierdolone', 'pierdolony', 'pierdołki', 'pierdzący', 'pierdzieć',
            'pierdziec', 'pizda', 'pizdą', 'pizde', 'pizdę', 'piździe', 'pizdzie',
            'pizdnąć', 'pizdnac', 'pizdu', 'podpierdalać', 'podpierdalac',
            'podpierdala', 'podpierdalający', 'podpierdalajacy', 'podpierdolić',
            'podpierdolic', 'podpierdoli', 'pojeb', 'pojeba', 'pojebami',
            'pojebani', 'pojebanego', 'pojebanemu', 'pojebani', 'pojebany',
            'pojebanych', 'pojebanym', 'pojebanymi', 'pojebem', 'pojebać',
            'pojebac', 'pojebalo', 'popierdala', 'popierdalac', 'popierdalać',
            'popierdolić', 'popierdolic', 'popierdoli', 'popierdolonego',
            'popierdolonemu', 'popierdolonym', 'popierdolone', 'popierdoleni',
            'popierdolony', 'porozpierdalać', 'porozpierdala', 'porozpierdalac',
            'poruchac', 'poruchać', 'przejebać', 'przejebane', 'przejebac',
            'przyjebali', 'przepierdalać', 'przepierdalac', 'przepierdala',
            'przepierdalający', 'przepierdalajacy', 'przepierdalająca',
            'przepierdalajaca', 'przepierdolić', 'przepierdolic', 'przyjebać',
            'przyjebac', 'przyjebie', 'przyjebała', 'przyjebala', 'przyjebał',
            'przyjebal', 'przypieprzać', 'przypieprzac', 'przypieprzający',
            'przypieprzajacy', 'przypieprzająca', 'przypieprzajaca',
            'przypierdalać', 'przypierdalac', 'przypierdala', 'przypierdoli',
            'przypierdalający', 'przypierdalajacy', 'przypierdolić',
            'przypierdolic', 'qrwa', 'rozjebać', 'rozjebac', 'rozjebie',
            'rozjebała', 'rozjebią', 'rozpierdalać', 'rozpierdalac', 'rozpierdala',
            'rozpierdolić', 'rozpierdolic', 'rozpierdole', 'rozpierdoli',
            'rozpierducha', 'skurwić', 'skurwiel', 'skurwiela', 'skurwielem',
            'skurwielu', 'skurwysyn', 'skurwysynów', 'skurwysynow', 'skurwysyna',
            'skurwysynem', 'skurwysynu', 'skurwysyny', 'skurwysyński',
            'skurwysynski', 'skurwysyństwo', 'skurwysynstwo', 'spieprzać',
            'spieprzac', 'spieprza', 'spieprzaj', 'spieprzajcie', 'spieprzają',
            'spieprzaja', 'spieprzający', 'spieprzajacy', 'spieprzająca',
            'spieprzajaca', 'spierdalać', 'spierdalac', 'spierdala', 'spierdalał',
            'spierdalała', 'spierdalal', 'spierdalalcie', 'spierdalala',
            'spierdalający', 'spierdalajacy', 'spierdolić', 'spierdolic',
            'spierdoli', 'spierdoliła', 'spierdoliło', 'spierdolą', 'spierdola',
            'srać', 'srac', 'srający', 'srajacy', 'srając', 'srajac', 'sraj',
            'sukinsyn', 'sukinsyny', 'sukinsynom', 'sukinsynowi', 'sukinsynów',
            'sukinsynow', 'śmierdziel', 'udupić', 'ujebać', 'ujebac', 'ujebał',
            'ujebal', 'ujebana', 'ujebany', 'ujebie', 'ujebała', 'ujebala',
            'upierdalać', 'upierdalac', 'upierdala', 'upierdoli', 'upierdolić',
            'upierdolic', 'upierdoli', 'upierdolą', 'upierdola', 'upierdoleni',
            'wjebać', 'wjebac', 'wjebie', 'wjebią', 'wjebia', 'wjebiemy',
            'wjebiecie', 'wkurwiać', 'wkurwiac', 'wkurwi', 'wkurwia', 'wkurwiał',
            'wkurwial', 'wkurwiający', 'wkurwiajacy', 'wkurwiająca', 'wkurwiajaca',
            'wkurwić', 'wkurwic', 'wkurwi', 'wkurwiacie', 'wkurwiają', 'wkurwiali',
            'wkurwią', 'wkurwia', 'wkurwimy', 'wkurwicie', 'wkurwiacie', 'wkurwić',
            'wkurwic', 'wkurwia', 'wpierdalać', 'wpierdalac', 'wpierdalający',
            'wpierdalajacy', 'wpierdol', 'wpierdolić', 'wpierdolic', 'wpizdu',
            'wyjebać', 'wyjebac', 'wyjebali', 'wyjebał', 'wyjebac', 'wyjebała',
            'wyjebały', 'wyjebie', 'wyjebią', 'wyjebia', 'wyjebiesz', 'wyjebie',
            'wyjebiecie', 'wyjebiemy', 'wypieprzać', 'wypieprzac', 'wypieprza',
            'wypieprzał', 'wypieprzal', 'wypieprzała', 'wypieprzala', 'wypieprzy',
            'wypieprzyła', 'wypieprzyla', 'wypieprzył', 'wypieprzyl', 'wypierdal',
            'wypierdalać', 'wypierdalac', 'wypierdala', 'wypierdalaj',
            'wypierdalał', 'wypierdalal', 'wypierdalała', 'wypierdalala',
            'wypierdalać', 'wypierdolić', 'wypierdolic', 'wypierdoli',
            'wypierdolimy', 'wypierdolicie', 'wypierdolą', 'wypierdola',
            'wypierdolili', 'wypierdolił', 'wypierdolil', 'wypierdoliła',
            'wypierdolila', 'zajebać', 'zajebac', 'zajebie', 'zajebią', 'zajebia',
            'zajebiał', 'zajebial', 'zajebała', 'zajebiala', 'zajebali', 'zajebana',
            'zajebani', 'zajebane', 'zajebany', 'zajebanych', 'zajebanym',
            'zajebanymi', 'zajebiste', 'zajebisty', 'zajebistych', 'zajebista',
            'zajebistym', 'zajebistymi', 'zajebiście', 'zajebiscie', 'zapieprzyć',
            'zapieprzyc', 'zapieprzy', 'zapieprzył', 'zapieprzyl', 'zapieprzyła',
            'zapieprzyla', 'zapieprzą', 'zapieprza', 'zapieprzy', 'zapieprzymy',
            'zapieprzycie', 'zapieprzysz', 'zapierdala', 'zapierdalać',
            'zapierdalac', 'zapierdalaja', 'zapierdalał', 'zapierdalaj',
            'zapierdalajcie', 'zapierdalała', 'zapierdalala', 'zapierdalali',
            'zapierdalający', 'zapierdalajacy', 'zapierdolić', 'zapierdolic',
            'zapierdoli', 'zapierdolił', 'zapierdolil', 'zapierdoliła',
            'zapierdolila', 'zapierdolą', 'zapierdola', 'zapierniczać',
            'zapierniczający', 'zasrać', 'zasranym', 'zasrywać', 'zasrywający',
            'zesrywać', 'zesrywający', 'zjebać', 'zjebac', 'zjebał', 'zjebal',
            'zjebała', 'zjebala', 'zjebana', 'zjebią', 'zjebali', 'zjeby'];


        for($i = 0; $i<sizeof($arr); $i++){
            if(Str::contains($request['body'], $arr[$i])){
                $message = 'There was an error!';
            }elseif($request->user()->posts()->save($post)){
                $message = 'Post Successfully created!';
            }
        }


        return redirect()->route('dashboard')->with(['message' => $message]);
    }

    public function  getDeletePost($post_id)
    {
        $post = Post::where('id', $post_id)->first();
        if(Auth::user() != $post->user){
            return redirect()->back();
        }
        $post->delete();
        return redirect()->route('dashboard')->with(['message' => 'Succesfully deleted!']);
    }

    public function postEditPost(Request $request)
    {
        $this->validate($request, [
            'body' => 'required'
        ]);
        $post = Post::find($request['postId']);
        if(Auth::user() != $post->user){
            return redirect()->back();
        }
        $post->body = $request['body'];
        $post->update();
        return response()->json(['new_body' => $post->body], 200);
    }

    public function postLikePost(Request $request)
    {
        $post_id = $request['postId'];
        $is_like = $request['isLike'] === 'true';
        $update = false;
        $post = Post::find($post_id);
        if (!$post) {
            return null;
        }
        $user = Auth::user();
        $like = $user->likes()->where('post_id', $post_id)->first();
        if ($like) {
            $already_like = $like->like;
            $update = true;
            if ($already_like == $is_like) {
                $like->delete();
                return null;
            }
        } else {
            $like = new Like();
        }
        $like->like = $is_like;
        $like->user_id = $user->id;
        $like->post_id = $post->id;
        if ($update) {
            $like->update();
        } else {
            $like->save();
        }
        return null;
    }
}