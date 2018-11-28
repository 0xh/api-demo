<?php

namespace App\Repositories;

use App\Models\Pet;
use InfyOm\Generator\Common\BaseRepository;
use App\Models\Jump;
use App\Models\Nap;
use App\Models\Roll;
use App\Models\Smile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
class PetRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description',
        'device_id',
        'animal_id',
        'breed_id',
        'UUID'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Pet::class;
    }

    public function makeError($message)
    {
        $res = [
            'success' => false,
            'message' => $message,
        ];

        return $res;
    }

    public function getPet($id){
        $pet = Pet::where('UUID', $id)->with('animal', 'breed', 'device')->first();
        $pet['avatar'] = $pet->getAvatar();
        if($pet->pet_fence){
            $pet['fence'] = $pet->pet_fence->fence;
        };
        return $pet;
    }

    public function getJumpsOfPet($id){

        $pet = Pet::where('UUID', $id)->first();

        if (empty($pet)) {
            return $this->makeError("Pet not found");
        }

        $jumps = $pet->jumps;


        return $jumps;
    }

    public function getNapsOfPet($id){
        $pet = Pet::where('UUID', $id)->first();

        if (empty($pet)) {
            return $this->makeError("Pet not found");
        }

        $naps = $pet->naps;

        return $naps;
    }

    public function getRollsOfPet($id){
        $pet = Pet::where('UUID', $id)->first();

        if (empty($pet)) {
            return $this->makeError("Pet not found");
        }

        $rolls = $pet->rolls;

        return $rolls;
    }

    public function getSmilesOfPet($id){
        $pet = Pet::where('UUID', $id)->first();

        if (empty($pet)) {
            return $this->makeError("Pet not found");
        }

        $smiles = $pet->smiles;

        return $smiles;
    }

    public function all($columns = ['*']){
        $pets = Pet::orderBy('id','DESC')->with(array('breed' => function($query){
                $query->select('id','name');
            }))->with(array('animal' => function($query){
                $query->select('id','name');
            }))->with(array('device' => function($query){
                $query->select('id','name');
            }))->get();
        return $pets;
    }
    public function getPets($userID){

        $pets = Pet::orderBy('id','DESC')->where('user_id',$userID)->with(array('breed' => function($query){
                $query->select('id','name');
            }))->with(array('animal' => function($query){
                $query->select('id','name');
            }))->with(array('device' => function($query){
                $query->select('id','name');
            }))->get();
        $arrPets = $pets->toArray();
        foreach($pets as $key => $pet){
            $avatar = $pet->getAvatar();
            $arrPets[$key]['url'] = $avatar;
        }
        return $arrPets;
    }
    
    public function getJumpsInTimeOfPet(array $attributes,$id){
        $pet = Pet::where('UUID', $id)->first();

        if (empty($pet)) {
            return $this->makeError("Pet not found");
        }
        if($attributes['day']){
            $time = $attributes['day'];
            $jumps = Jump::where('pet_id',$pet->id)->where('created_at','LIKE','%'.$time.'%')->get();

        }elseif($attributes['startDay'] && $attributes['endDay']){
            $startDay = $attributes['startDay'];
            $endDay = $attributes['endDay'];
            $jumps = Jump::where('pet_id',$pet->id)->whereBetween(DB::raw('date(created_at)'),[$startDay,$endDay])->get();
        }else{
            return $this->makeError("Pet not found"); 
        }
        return $jumps;
    }
    public function getNapsInTimeOfPet(array $attributes,$id){
        $pet = Pet::where('UUID', $id)->first();

        if (empty($pet)) {
            return $this->makeError("Pet not found");
        }
        if($attributes['day']){
            $time = $attributes['day'];
            $naps = Nap::where('pet_id',$pet->id)->where('created_at','LIKE','%'.$time.'%')->get();

        }elseif($attributes['startDay'] && $attributes['endDay']){
            $startDay = $attributes['startDay'];
            $endDay = $attributes['endDay'];
            $naps = Nap::where('pet_id',$pet->id)->whereBetween(DB::raw('date(created_at)'),[$startDay,$endDay])->get();
        }else{
            return $this->makeError("Pet not found"); 
        }
        return $naps;
    }
    public function getRollsInTimeOfPet(array $attributes,$id){
        $pet = Pet::where('UUID', $id)->first();

        if (empty($pet)) {
            return $this->makeError("Pet not found");
        }
        if($attributes['day']){
            $time = $attributes['day'];
            $rolls = Roll::where('pet_id',$pet->id)->where('created_at','LIKE','%'.$time.'%')->get();

        }elseif($attributes['startDay'] && $attributes['endDay']){
            $startDay = $attributes['startDay'];
            $endDay = $attributes['endDay'];
            $rolls = Roll::where('pet_id',$pet->id)->whereBetween(DB::raw('date(created_at)'),[$startDay,$endDay])->get();
        }else{
            return $this->makeError("Pet not found"); 
        }
        return $rolls;
    }
    public function getSmilesInTimeOfPet(array $attributes,$id){
        $pet = Pet::where('UUID', $id)->first();

        if (empty($pet)) {
            return $this->makeError("Pet not found");
        }
        if($attributes['day']){
            $time = $attributes['day'];
            $smiles = Smile::where('pet_id',$pet->id)->where('created_at','LIKE','%'.$time.'%')->get();

        }elseif($attributes['startDay'] && $attributes['endDay']){
            $startDay = $attributes['startDay'];
            $endDay = $attributes['endDay'];
            $smiles = Smile::where('pet_id',$pet->id)->whereBetween(DB::raw('date(created_at)'),[$startDay,$endDay])->get();
        }else{
            return $this->makeError("Pet not found"); 
        }
        return $smiles;
    }

    public function getPetLocation($userID){
        $datas = [];
        $petArray = [];

        $pets = Pet::orderBy('id','DESC')->where('user_id', $userID)->with(array('device' => function($query){
                $query->select('id','name', 'battery');
            }))->with(array('animal' => function($query){
                $query->select('id','name');
            }))->with(array('breed' => function($query){
                $query->select('id','name');
            }))->get();

        $petArray = $pets->toArray();

        foreach ($pets as $key => $pet) {
            if($pet->device){
                $location = $pet->device->locations()->latest()->first();
            }else{
                $location = null;
            }

            $petArray[$key]['url'] = $pet->getAvatar();
            $data = array_add($petArray[$key],'location', $location);
            array_push($datas, $data);
        }
        return $datas;
    }

    public function getPetLocationPaginate($userId, $per_page){
        $petArray = [];

        $pets = Pet::orderBy('id','DESC')
            ->where('user_id', $userId)
            ->with(array('device' => function($query){
                $query->select('id','name', 'battery');
            }))
            ->with(array('animal' => function($query){
                $query->select('id','name');
            }))
            ->with(array('breed' => function($query){
                $query->select('id','name');
            }))
            ->with('device.locations')
            ->paginate($per_page);

        return $pets;
    }

    public function getPetOfUserWithAvatar($id){
        $pets = Pet::orderBy('id','DESC')->where('user_id',$id)->get();
        $arrPets = $pets->toArray();
        foreach ($pets as $key => $pet) {
            $image =  $pet->image;
            if($image){
                if(Storage::disk('public')->exists('pets/'.$pet->id.'/'.$image['url'])){
                    $arrPets[$key]['avatar'] = url('storage/pets/'.$pet->id.'/'.$image['url']);
                }else{
                    $arrPets[$key]['avatar'] = url('/img/default/default-avatar-pet.png');
                }
            }else{
                $arrPets[$key]['avatar'] = url('/img/default/default-avatar-pet.png');
            }
            $device = $pet->device;
            if($device){
                $locations = $device->locations->sortByDesc('id');
                if($locations){
                    $arrPets[$key]['location'] = $locations;
                }
            }  
        } 
        return $arrPets;
    }

    public function getAllPets(){
        $pets = Pet::orderBy('id','DESC')->get();
        $arrPets = $pets->toArray();
        foreach ($pets as $key => $pet) {
            $image =  $pet->image;
            if($image){
                if(Storage::disk('public')->exists('pets/'.$pet->id.'/'.$image['url'])){
                    $arrPets[$key]['avatar'] = url('storage/pets/'.$pet->id.'/'.$image['url']);
                }else{
                    $arrPets[$key]['avatar'] = url('/img/default/default-avatar-pet.png');
                }
            }else{
                $arrPets[$key]['avatar'] = url('/img/default/default-avatar-pet.png');
            }
            $device = $pet->device;
            if($device){
                $locations = $device->where('id',$pet['device_id'])->with(['locations'=>function($query){
                    $query->latest()->first();
                }])->get();
                if($locations){
                    $arrPets[$key]['device']= $locations;
                }
            }  
        }
        return $arrPets;
    }

    public function getPetsNoFence($id){
        $pets = Pet::where('user_id',$id)->get();
        $array = [];
        foreach ($pets as $key => $pet) {
            $fence =  $pet->hasFence()->toArray();
            if(empty($fence)){
                array_push($array,$pet->toArray());
            }
        }
        return $array;
    }
    // NEW API GET ACTIVITY PET
    // -- Jumps --
    public function getJumpsByDay(array $attributes,$id){
        $pet = Pet::where('UUID', $id)->first();
        $day = Carbon::parse($attributes['day'])->format('Y-m-d');

        $jumps = Jump::Where('pet_id',$pet->id)->whereDate('created_at',date($day))->groupBy(DB::raw('HOUR(created_at)'))
               ->selectRaw('HOUR(created_at) as hour, sum(amount) as amount')
               ->get();

        return $jumps;
    }
    public function getJumpsByRangeDay(array $attributes,$id){
        $pet = Pet::where('UUID', $id)->first();
        $startDay = Carbon::parse($attributes['startDay'])->format('Y-m-d');

        $endDay = Carbon::parse($attributes['endDay'])->format('Y-m-d');

        $jumps = Jump::Where('pet_id',$pet->id)->whereBetween(DB::raw('date(created_at)'),[$startDay,$endDay])
                ->groupBy(DB::raw('MONTH(created_at)'))
                ->groupBy(DB::raw('DAY(created_at)'))
               ->selectRaw('DAY(created_at) as day,MONTH(created_at) as month, sum(amount) as amount')
               ->get();

        return $jumps;
    }
    public function getJumpsByMonth(array $attributes,$id){
        $pet = Pet::where('UUID', $id)->first();
        $month = Carbon::parse($attributes['month'])->format('m');

        $year = Carbon::parse($attributes['month'])->format('Y');

        $jumps = Jump::Where('pet_id',$pet->id)->whereMonth('created_at',$month)->whereYear('created_at',$year)->groupBy(DB::raw('DAY(created_at)'))
               ->selectRaw('DAY(created_at) as day, sum(amount) as amount')
               ->get();

        return $jumps;
    }
    public function getJumpsByRangeMonth(array $attributes,$id){
        $pet = Pet::where('UUID', $id)->first();
        $startMonth = Carbon::parse($attributes['startMonth'])->format('Y-m-d');

        $endMonth = Carbon::parse($attributes['endMonth'])->endOfMonth()->format('Y-m-d');

        $jumps = Jump::Where('pet_id',$pet->id)->whereBetween(DB::raw('date(created_at)'),[$startMonth,$endMonth])->groupBy(DB::raw('MONTH(created_at)'))
               ->selectRaw('MONTH(created_at) as month, sum(amount) as amount')
               ->get();

        return $jumps;
    }
    // -- Naps --
    public function getNapsByDay(array $attributes,$id){
        $pet = Pet::where('UUID', $id)->first();
        $day = Carbon::parse($attributes['day'])->format('Y-m-d');

        $naps = Nap::Where('pet_id',$pet->id)->whereDate('created_at',date($day))->groupBy(DB::raw('HOUR(created_at)'))
               ->selectRaw('HOUR(created_at) as hour, sum(amount) as amount')
               ->get();

        return $naps;
    }
    public function getNapsByRangeDay(array $attributes,$id){
        $pet = Pet::where('UUID', $id)->first();
        $startDay = Carbon::parse($attributes['startDay'])->format('Y-m-d');

        $endDay = Carbon::parse($attributes['endDay'])->format('Y-m-d');

        $naps = Nap::Where('pet_id',$pet->id)->whereBetween(DB::raw('date(created_at)'),[$startDay,$endDay])
                ->groupBy(DB::raw('MONTH(created_at)'))
                ->groupBy(DB::raw('DAY(created_at)'))
               ->selectRaw('DAY(created_at) as day,MONTH(created_at) as month, sum(amount) as amount')
               ->get();

        return $naps;
    }
    public function getNapsByMonth(array $attributes,$id){
        $pet = Pet::where('UUID', $id)->first();
        $month = Carbon::parse($attributes['month'])->format('m');

        $year = Carbon::parse($attributes['month'])->format('Y');

        $naps = Nap::Where('pet_id',$pet->id)->whereMonth('created_at',$month)->whereYear('created_at',$year)->groupBy(DB::raw('DAY(created_at)'))
               ->selectRaw('DAY(created_at) as day, sum(amount) as amount')
               ->get();

        return $naps;
    }
    public function getNapsByRangeMonth(array $attributes,$id){
        $pet = Pet::where('UUID', $id)->first();
        $startMonth = Carbon::parse($attributes['startMonth'])->format('Y-m-d');

        $endMonth = Carbon::parse($attributes['endMonth'])->endOfMonth()->format('Y-m-d');

        $naps = Nap::Where('pet_id',$pet->id)->whereBetween(DB::raw('date(created_at)'),[$startMonth,$endMonth])->groupBy(DB::raw('MONTH(created_at)'))
               ->selectRaw('MONTH(created_at) as month, sum(amount) as amount')
               ->get();

        return $naps;
    }
    // -- Smiles --
    public function getSmilesByDay(array $attributes,$id){
        $pet = Pet::where('UUID', $id)->first();
        $day = Carbon::parse($attributes['day'])->format('Y-m-d');

        $smiles = Smile::Where('pet_id',$pet->id)->whereDate('created_at',date($day))->groupBy(DB::raw('HOUR(created_at)'))
               ->selectRaw('HOUR(created_at) as hour, sum(amount) as amount')
               ->get();

        return $smiles;
    }
    public function getSmilesByRangeDay(array $attributes,$id){
        $pet = Pet::where('UUID', $id)->first();
        $startDay = Carbon::parse($attributes['startDay'])->format('Y-m-d');

        $endDay = Carbon::parse($attributes['endDay'])->format('Y-m-d');

        $smiles = Smile::Where('pet_id',$pet->id)->whereBetween(DB::raw('date(created_at)'),[$startDay,$endDay])
                ->groupBy(DB::raw('MONTH(created_at)'))
                ->groupBy(DB::raw('DAY(created_at)'))
               ->selectRaw('DAY(created_at) as day,MONTH(created_at) as month, sum(amount) as amount')
               ->get();

        return $smiles;
    }
    public function getSmilesByMonth(array $attributes,$id){
        $pet = Pet::where('UUID', $id)->first();
        $month = Carbon::parse($attributes['month'])->format('m');

        $year = Carbon::parse($attributes['month'])->format('Y');

        $smiles = Smile::Where('pet_id',$pet->id)->whereMonth('created_at',$month)->whereYear('created_at',$year)->groupBy(DB::raw('DAY(created_at)'))
               ->selectRaw('DAY(created_at) as day, sum(amount) as amount')
               ->get();

        return $smiles;
    }
    public function getSmilesByRangeMonth(array $attributes,$id){
        $pet = Pet::where('UUID', $id)->first();
        $startMonth = Carbon::parse($attributes['startMonth'])->format('Y-m-d');

        $endMonth = Carbon::parse($attributes['endMonth'])->endOfMonth()->format('Y-m-d');

        $smiles = Smile::Where('pet_id',$pet->id)->whereBetween(DB::raw('date(created_at)'),[$startMonth,$endMonth])->groupBy(DB::raw('MONTH(created_at)'))
               ->selectRaw('MONTH(created_at) as month, sum(amount) as amount')
               ->get();

        return $smiles;
    }
    // -- Rolls --
    public function getRollsByDay(array $attributes,$id){
        $pet = Pet::where('UUID', $id)->first();
        $day = Carbon::parse($attributes['day'])->format('Y-m-d');

        $rolls = Roll::Where('pet_id',$pet->id)->whereDate('created_at',date($day))->groupBy(DB::raw('HOUR(created_at)'))
               ->selectRaw('HOUR(created_at) as hour, sum(amount) as amount')
               ->get();

        return $rolls;
    }
    public function getRollsByRangeDay(array $attributes,$id){
        $pet = Pet::where('UUID', $id)->first();
        $startDay = Carbon::parse($attributes['startDay'])->format('Y-m-d');

        $endDay = Carbon::parse($attributes['endDay'])->format('Y-m-d');

        $rolls = Roll::Where('pet_id',$pet->id)->whereBetween(DB::raw('date(created_at)'),[$startDay,$endDay])
                ->groupBy(DB::raw('MONTH(created_at)'))
                ->groupBy(DB::raw('DAY(created_at)'))
                ->selectRaw('DAY(created_at) as day,MONTH(created_at) as month, sum(amount) as amount')
                ->get();

        return $rolls;
    }
    public function getRollsByMonth(array $attributes,$id){
        $pet = Pet::where('UUID', $id)->first();
        $month = Carbon::parse($attributes['month'])->format('m');

        $year = Carbon::parse($attributes['month'])->format('Y');

        $rolls = Roll::Where('pet_id',$pet->id)->whereMonth('created_at',$month)->whereYear('created_at',$year)->groupBy(DB::raw('DAY(created_at)'))
               ->selectRaw('DAY(created_at) as day, sum(amount) as amount')
               ->get();

        return $rolls;
    }
    public function getRollsByRangeMonth(array $attributes,$id){
        $pet = Pet::where('UUID', $id)->first();
        $startMonth = Carbon::parse($attributes['startMonth'])->format('Y-m-d');

        $endMonth = Carbon::parse($attributes['endMonth'])->endOfMonth()->format('Y-m-d');

        $rolls = Roll::Where('pet_id',$pet->id)->whereBetween(DB::raw('date(created_at)'),[$startMonth,$endMonth])->groupBy(DB::raw('MONTH(created_at)'))
               ->selectRaw('MONTH(created_at) as month, sum(amount) as amount')
               ->get();

        return $rolls;
    }

    public function getListPetsOfUserNofence($userId){
        $pets = Pet::where('user_id', $userId)->where('fence_id', null)->select('id', 'name')->get();

        foreach ($pets as $key => $value) {
            $pets[$key]['avatar'] = $value->getAvatar();
        }

        return $pets;
    }

    public function getListPetsOfUser($userId){
        $pets = Pet::where('user_id', $userId)->select('id', 'name')->get();

        foreach ($pets as $key => $value) {
            $pets[$key]['avatar'] = $value->getAvatar();
        }

        return $pets;
    }

}
