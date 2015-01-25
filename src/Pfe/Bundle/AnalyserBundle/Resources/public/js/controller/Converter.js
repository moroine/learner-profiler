/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var Converter = {
    isNear: function (placeA, placeB) {
        var latA = Math.round(placeA.latitude);
        var latB = Math.round(placeB.latitude);
        var longA = Math.round(placeA.longitude);
        var longB = Math.round(placeB.longitude);

        if (Math.abs(latB - latA) < 2 && Math.abs(longB - longA) < 2) {
            return true;
        }

        return false;
    }
};