import Home from './calendar_view/Home';
export default{
    mode: 'history',
    // base: '/ntrends/',
    routes:[
        {
            path: '/calendar',
            component: Home,
            name: 'Home',
            // props: {name: this.$root.name}
        },
        // {
        //     path: '*',
        //     component: Home,
        //     name: 'Home',
        //     // props: {name: this.$root.name}
        // }
    ]

};