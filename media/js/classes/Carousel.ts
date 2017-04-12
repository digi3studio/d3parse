/**
 * Created by colinleung on 10/4/2017.
 */
/// <reference path="../../../ts/typings/globals/jquery/index.d.ts"/>
/// <reference path="../../../ts/typings/globals/hammerjs/index.d.ts"/>

///<amd-dependency path="jquery" />
///<amd-dependency path="hammerjs" />

export class Carousel{
  constructor(node:JQuery){
    let hammertime = new Hammer(node.get(0));
    hammertime.get('swipe').set({ direction: Hammer.DIRECTION_HORIZONTAL });
    hammertime.on('swiperight',function(e){
      $(e.target).parents('.carousel').attr('data-active', '1');
    });

    node.find('.page-control').on('click', function(e){
      e.preventDefault();
      e.stopPropagation();
      $(this).parents('.carousel').attr('data-active', $(this).attr('data-item'));
    });
  }
}