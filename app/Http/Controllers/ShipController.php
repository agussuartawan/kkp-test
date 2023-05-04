<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShipRequest;
use App\Http\Requests\UpdateShipRequest;
use App\Models\Ship;
use Illuminate\Support\Facades\Storage;

class ShipController extends Controller
{

    public function getAll()
    {
        return Ship::orderBy('created_at', 'desc')->get();
    }

    public function shipAllPublic()
    {
        $ships = $this->getAll();
        return $ships->makeHidden('licence_doc');
    }

    public function create(ShipRequest $request)
    {
        $ship = $request->validated();
        $photo = $ship['photo'];
        if($photo) {
            $filename =  time().'_ship_photo.'.$photo->extension();
            $photo->storeAs('public/images/ship-photo', $filename);
            $ship['photo'] = $filename;
        }

        $doc = $ship['licence_doc'];
        if ($doc) {
            $filename =  time().'_ship_licence_doc.'.$doc->extension();
            $doc->storeAs('public/document/ship-document', $filename);
            $ship['licence_doc'] = $filename;
        }

        return Ship::create($ship);

    }

    public function verifShip(Ship $ship, string $verif)
    {
        if ($verif != 'reject' && $verif != 'verif') abort(404);
        if ($verif == 'verif') $ship->is_approved = true;
        if ($verif == 'reject') {
            $ship->note = request('note');
            $ship->is_approved = false;
        }

        $ship->save();
        return $ship;
    }

    public function delete(Ship $ship)
    {
        return $ship->delete();
    }

    public function update(UpdateShipRequest $request, Ship $ship)
    {
        $shipData = $request->validated();
        $photo = $request->photo;
        if($photo) {
            Storage::delete($ship->photo);
            $filename =  time().'_ship_photo.'.$photo->extension();
            $photo->storeAs('public/images/ship-photo', $filename);
            $shipData['photo'] = $filename;
        }

        $doc = $request->licence_doc;
        if ($doc) {
            Storage::delete($ship->licence_doc);
            $filename =  time().'_ship_licence_doc.'.$doc->extension();
            $doc->storeAs('public/document/ship-document', $filename);
            $shipData['licence_doc'] = $filename;
        }

        $ship->update($shipData);
        return $ship;
    }

}
