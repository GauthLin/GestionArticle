import { Component, Input } from '@angular/core';
import { ActivatedRoute, Params }   from '@angular/router';
import { Location }                 from '@angular/common';

import 'rxjs/add/operator/switchMap';


import { HeroService } from './hero.service';


@Component({
    moduleId: module.id,
    selector: 'add-hero',
    templateUrl: 'add-hero.component.html',
    styleUrls: [ 'hero-detail.component.css' ]
})


export class AddHeroComponent {
    constructor(
        private heroService: HeroService,
        private route: ActivatedRoute,
        private location: Location
    ) {}
    
    
    addHero(firstname, lastname, email): void {
        this.heroService.create(firstname, lastname, email);
    }

    goBack(): void {
        this.location.back();
    }
}

