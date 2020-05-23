export default class Gate {
    constructor(user){
        this.user = user;
    }
    isSuperAdmin(){
        return this.user.type === 'super';
    }
    isAdmin(){
        return this.user.type === 'admin';
    }
    isEmployee(){
        return this.user.type === 'employee';
    }
    isOrg(){
        return this.user.type === 'org';
    }
    isTeam(){
        return this.user.type === 'team';
    }
    isProfessional(){
        return this.user.type === 'pro';
    }
    isUser(){
        return this.user.type === 'user';
    }

    isStudent(){
        return this.user.type === 'student';
    }
    isGroup(){
        return this.user.type === 'group';
    }
}
