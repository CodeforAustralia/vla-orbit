<template>
    <div>
        <div class="tabbable-line" v-if="logInfo">
            <h4>{{title}}</h4>

            <ul class="nav nav-tabs">
                <li
                    v-for="(item, index) in logInfo"
                    :key="index">
                    <a
                        data-toggle="tab"
                        :href="'#' + parseText(index) + parseText(title)"
                        >
                        {{ index }}
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div
                    class="tab-pane fade"
                    v-for="(item, index) in logInfo"
                    :key="index"
                    :id="parseText(index) + parseText(title)"
                    >
                    <ul>
                        <li  v-for="(attr, index_name) in item" :key="index_name">
                            <p v-html="getTextDescription(index_name, attr)">  </p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props:['title', 'logInfo'],
    methods:{
        getTextDescription(label, data){
            if(label === 'suburbs' || label === 'lga' ) {
                return data.map(item => item.text ).join(', ');
            } else {
                return label + ': ' + data;
            }
        },
        parseText(text) {
            return text.split(' ',).join('-').split('/',).join('-').split(':',).join('-');
        }
    }
}
</script>