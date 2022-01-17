export class User {
    constructor(id,name,email,userAgent,entranceTime,visitCount,userIp,lastUpdate,isOnLine){
        this.id = id;
        this.name = name;
        this.email = email;
        this.userAgent = userAgent;
        this.entranceTime = entranceTime;
        this.visitCount = visitCount;
        this.userIp = userIp;
        this.lastUpdate = lastUpdate;
        this.isOnLine = isOnLine;
    }
}