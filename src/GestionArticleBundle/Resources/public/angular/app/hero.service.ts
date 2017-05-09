import { Injectable }    from '@angular/core';
import { Headers, Http, URLSearchParams } from '@angular/http';
import {Hero} from "./hero";

import 'rxjs/add/operator/toPromise';

@Injectable()
export class HeroService {
    private heroesUrl = 'http://localhost/GestionArticle/web/app_dev.php/api/auteur';  // URL to web api
    private headers = new Headers({'Content-Type': 'application/x-www-form-urlencoded'});

    constructor(private http: Http) { }

    getHero(id: number): Promise<Hero> {
        const url = `${this.heroesUrl}/${id}`;
        return this.http.get(url)
            .toPromise()
            .then(response => {
                return response.json()
            })
            .catch(this.handleError);
    }

    getHeroes(): Promise<Hero[]> {
        return this.http
            .get(this.heroesUrl)
            .toPromise()
            .then(response => response.json() as Hero[])
            .catch(this.handleError);
    }

    create(firstname: string, lastname: string, email: string) {
        let form = new URLSearchParams();
        
        form.set('firstname', firstname);
        form.set('lastname', lastname);
        form.set('email', email);
        
        return this.http
            .post(this.heroesUrl, form.toString(), {headers: this.headers})
            .toPromise()
            .then(res => res.json())
            .catch(this.handleError);
    }

    save(hero: Hero) {
        let form = new URLSearchParams();

        form.set('id', hero.id.toString());
        form.set('firstname', hero.firstname);
        form.set('lastname', hero.lastname);
        form.set('email', hero.email);

        return this.http
            .put(this.heroesUrl, form.toString(), {headers: this.headers})
            .toPromise()
            .then(res => res.json())
            .catch(this.handleError);
    }
    
    del(hero: Hero) {
        const url = `${this.heroesUrl}/${hero.id}`;
        return this.http
            .delete(url)
            .toPromise()
            .then(response => {
                return response.json()
            })
            .catch(this.handleError);
    }


    private handleError(error: any): Promise<any> {
        console.error('An error occurred', error); // for demo purposes only
        return Promise.reject(error.message || error);
    }
}
