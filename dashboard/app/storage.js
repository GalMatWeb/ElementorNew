
export const Storage = {
    getLS: ()=>{

    },
    getSS: (key)=>{
        const st = window.sessionStorage;
        return st.getItem(key);
    },
    setSS:(key,val)=>{
        const st = window.sessionStorage;
        st.setItem(key,val);
    },
    setLS:(key,val)=>{
        const st = window.sessionStorage;
        st.setItem(key,val);
    },
    clearS: function () {
        const st = window.sessionStorage;
        st.clear();
    }
};


