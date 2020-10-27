import IModel from "./IModel";

export default class Meeting extends IModel {

    /**
     * Create a new motion
     * @param id
     * @param name
     * @param date
     */
    constructor(id, name, date ) {
        super();
        this.name = name;
        this.id = id;
        this.date = date;

    }

    readableDate(){

        const d = Date.parse(this.date);
        const ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(d);
        const mo = new Intl.DateTimeFormat('en', { month: 'long' }).format(d);
        const da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(d);
        let out =`${da} ${mo} ${ye}`;
        //
        // let o_date = new Intl.DateTimeFormat;
        // // const f_date = (m_ca, m_it) => Object({...m_ca, [m_it.type]: m_it.value});
        // const m_date = o_date.formatToParts().reduce(this.date, {});
        // let out = m_date.year + '-' + m_date.month + '-' + m_date.day ;
        // window.console.log(out);
        return out


    }
};
