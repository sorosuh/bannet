<?php
/*
 * @author Soroush Bagherian, Milad Mohammadi
 * @desc see @APIlink [blank]
 */

namespace App\Http\Controllers\api\v1;


use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\QueryRequest;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use JWTAuth;

class QueryController extends Costomizer
{
    protected $db; // Database instance
    protected $response, $table, $aggregate, $col, $join, $where, $limit, $offset, $row, $update, $afterQuery, $query, $search;

    public function __construct(Request $request)
    {
        if (isset($request->token)) {
            $this->user = JWTAuth::parseToken()->authenticate();
        }
        
        $this->response     = null;
        $this->table        = ($request->table) ? $request->table : null;
        $this->aggregate    = ($request->aggregate) ? $request->aggregate : null;
        $this->col          = ($request->col) ? $request->col : "*";
        $this->join         = ($request->join) ? $request->join : null;
        $this->where        = ($request->where) ? $request->where : null;
        $this->limit        = (isset($request->limit)) ? $request->limit : null;
        $this->offset       = (isset($request->offset)) ? $request->offset : null;
        $this->row          = ($request->row) ? $request->row : null;
        $this->update       = ($request->update) ? $request->update : null;
        $this->afterQuery   = ($request->afterQuery) ? $request->afterQuery : null;
        $this->Query        = ($request->Query) ? $request->Query : null;
        $this->search       = ($request->search) ? $request->search : null;
        
        $this->db = app('db')->table($this->table); // get instance from database
    }

    public function QueryBuilder(QueryRequest $request)
    {
        switch ($request->function) {
            case 'insert':
                $this->insert();
                break;
            case 'delete':
                $this->delete();
                break;
            case 'TRUNCATE_TABLE':
                $this->TRUNCATE_TABLE();
                break;
            case 'update':
                $this->update();
                break;
            case 'join':
                $this->join();
                break;
            case 'select':
                $this->select();
                break;
            default:
                $this->customQuery();
        }
        
        return $this->response;
    }

    /**
     * @return string
     * @debug true
     */
    protected function insert()
    {
        $this->row = $this->before_query((array)$this->row, 'insert');
        
        $insert = DB::table($this->table)->insert($this->row);
        $this->response = ($insert) ? 'success' : 'failed';
    }

    /**
     * @return string
     * @debug true
     */
    protected function delete()
    {
        $delete = DB::table($this->table)
                    ->where($this->where)
                    ->delete();
        $this->response = ($delete) ? 'success' : 'failed';
    }

    /**
     * @return string
     * @debug false
     */
    protected function TRUNCATE_TABLE()
    {
        $truncate = DB::table($this->table)->truncate();
        $this->response = ($truncate) ? 'success' : 'failed';
    }

    /**
     * @return string
     * @debug false
     */
    protected function update()
    {
        $this->update = $this->before_query((array)$this->update, 'update');
        $affected = DB::table($this->table)
                        ->where($this->where)
                        ->update($this->update);
        
        // return effected rows with afterQuery
        if($affected == 1 && isset($this->afterQuery['return'])) {
            $this->response = [
                'status'    => 'success',
                'effected'  => $this->update
            ];
            return;
        }
        
        $this->response = ($affected) ? 'success' : 'failed';
    }

    /**
     * @return \Illuminate\Database\Query\Builder
     * @debug true
     */   
    protected function join()
    {
        foreach($this->join as $func) {
            if(! isset($func['side']) || $func['side'] == 'both') {
                $this->db->join($func['table'], $func['one'], (isset($func['operator']) ? $func['operator'] : '='), $func['two']);
            } else if(isset($func['side']) && $func['side'] == 'right') {
                $this->db->rightJoin($func['table'], $func['one'], (isset($func['operator']) ? $func['operator'] : '='), $func['two']);
            } else if(isset($func['side']) && $func['side'] == 'left') {
                $this->db->leftJoin($func['table'], $func['one'], (isset($func['operator']) ? $func['operator'] : '='), $func['two']);
            }
        }
        $this->select();
    }

    /**
     * @return Collection
     * @debug false
     */
    protected function select()
    {
        if(is_null($this->aggregate)) {
            // check select
            if(is_string($this->col)) {
                $this->db->select(DB::raw($this->col));
            } else {
                $this->db->select($this->col);
            }
        }
        
        $this->condition();
        
        // check variety of aggregate methods
        if($this->aggregate == 'count') $this->response = $this->db->count();
        else if($this->aggregate == 'sum') $this->response = $this->db->sum($this->col);
        else if($this->aggregate == 'max') $this->response = $this->db->max($this->col);
        else if($this->aggregate == 'min') $this->response = $this->db->min($this->col);
        else if($this->aggregate == 'avg') $this->response = $this->db->avg($this->col);
        else if($this->aggregate == 'distinct') $this->response = $this->after_query($this->db->distinct()->get());
        
        else $this->response = $this->after_query($this->db->get());
        
    }

    /**
     * @return Collection
     * @debug false
     */
    protected function customQuery($query , $afterQuery)
    {
        $query = $this->db->select(DB::raw($query));
        $this->response = $this->after_query($this->db->get());
    }

}
