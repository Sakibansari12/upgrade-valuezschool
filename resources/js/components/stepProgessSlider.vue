<template>
    <div class="progressbar-container">
        <ul class="progressbar-step">
            <li :style="{ 'width': 98 / length + '%' }" :class="(i <= current) ? 'active-step' : ''" v-for="i in length"
                :key="i"></li>
        </ul>
    </div>
   
</template>
<script>
export default {
    props: ['length', 'current'],
    data() {
        return {
        }
    },
}
</script>

<style scoped>
.progressbar-container {
    /* width: 35%; */
    /* position: absolute; */
    z-index: 1;
    margin-top: 20px;

}

.progressbar-step {
    counter-reset: step;
    list-style: none;
    padding: 0;
}

.progressbar-step li {
    float: left;
    /* width: 14.28%; */
    position: relative;
    text-align: center;
}

.progressbar-step li:before {
    content: counter(step);
    counter-increment: step;
    width: 30px;
    height: 30px;
    border: 2px solid #bebebe;
    display: block;
    margin: 0 auto 10px auto;
    border-radius: 50%;
    line-height: 27px;
    background: white;
    color: #bebebe;
    text-align: center;
    font-weight: bold;
}

.progressbar-step li:after {
    content: '';
    position: absolute;
    width: 100%;
    height: 3px;
    background: #979797;
    top: 15px;
    left: -50%;
    z-index: -1;
}

.progressbar-step li.active-step:before {
    border-color: #324864;
    background: #324864;
    color: white
}

.progressbar-step li.active-step:after {
    background: #324864;
}

.progressbar-step li.active-step li:after {
    background: #324864;
}

.progressbar-step li.active-step li:before {
    border-color: #324864;
    background: #324864;
    color: white
}

.progressbar-step li:first-child:after {
    content: none;
}
</style>
