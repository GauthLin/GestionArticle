export class Hero {
    constructor(id: number, firstname: string, lastname: string, email: string) {
        this.id = id;
        this.firstname = firstname;
        this.lastname = lastname;
        this.email = email;
    }

    id: number;
    firstname: string;
    lastname: string;
    email: string;
}
