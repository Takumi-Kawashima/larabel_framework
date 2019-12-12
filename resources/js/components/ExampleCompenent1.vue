<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md8">
        <div class="card">
          <div class="card-header">{{ title }} <span class="badge badge-success">{{ cagegory_name }}</span></div>

          <div class="card-body text-center drill-body">
            <button class="btn btn-primary" @click="doDrill" v-if="!isStarted">
              START
            </button>
            <p v-if="isCountdown" style="font-size:100px;">{{ countDownNum }}</p>

            <template v-if="!isStarted && !isCountdown && !isEnd">
              <p>{{ timerNum }}</p>
              <span v-for="(word, index) in problemWords" :class="{'text-primary': index < countDownNum}">{{ word }}</span>
            </template>

            <template v-if="isEnd">
              <p>あなたのスコア</p>
              <p>{{ typingScore }}</p>
            </template>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import keyCodeMap from '../master/keymap'
export default {
  props: ['title', 'drill', 'category_name'],
  data: function(){
    return {
      countDownNum: 3,
      timerNum: 30,
      missNum: 0,
      wpm: 0,
      isStarted: false,
      isEnd: false,
      isCountDown: false,
      currentWordNum: 0,
      currentProblemNum: 0,
    }
  },
  computed: {
    problemText: function() {
      return this.drill['problem' + this.currentProblemNum]
    },
    problemWords: function() {
      return Array.from(this.drill['problem' + this.currentProblemNum])
    },
    problemKeyCodes: function(){
      if(!Array.from(this.drill['problem' + this.currentProblemNum]).length){
        return null
      }

      //テキストから問題のキーコード配列を生成
      let problemKeyCodes = []
      console.log(Array.from(this.drill['problem' + this.currentProblemNum]))
      Array.from(this.drill['problem' + this.currentProblemNum]).forEach((text) => { //textにthis.drill['problem'+this.currentProblemNum]の１つ１つが入ってくる。
        $.each(keyCodeMap, (keyText, keyCode) => {
          if(text === keyText){
            problemKeyCods.push(keyCode)
          }
        })
      })
      console.log(problemKeyCodes)
      return problemKeyCodes
    },
    //問題の文字数
    totalWordNum: function() {
      return problemKeyCodes.lenth
    },
    typingScore: function() {
      return (this.wpn * 2) * (1 - this.missNum / (this.wpn * 2))
    }
  },
  methods: {
    doDrill: function() {
      this.isStarted = true,
      this.countDown()
    },
    countDown: function() {
      const countSound = new Audio('../sounds/Countdown01-5.mp3')
      const startSound = new Audio('../sounds/Countdown01-6.mp3')

      this.countDown = true
      this.soundPlay(countSound)

      let timer = window.setInterval(() => {
        this.countDown -= 1

        if(this.countDown <= 0){
          this.countDown = false
          this.soundPlay(startSound)

          window.clearInterval(timer)
          this.countTimer()
          this.showFirstProblem()

          return
        }
      }, 1000)
    },
    showFirstProblem: function() {
      const okSound = new Audio('../sounds/punch-middle2.mp3')
      const ngSound = new Audio('../sounds/sword-clash4.mp3')
      const nextSound = new Audio('../sounds/punch-high2.mp3')

      $(window).on('keyup', e => {
        console.log(e.which)
        if(e.which === this.problemKeyCodes[this.currentWordNum]){
          console.log('正解！')

          this.soundPlay()

          ++this.currentWordNum
          ++this.wpm
          console.log('現在の回答数目：' + this.currentWordNum)
          
          if(this.totalWordNum === this.currentWordNum){
            console.log('次の問題へ！')
            ++this.currentWordNum
            this.currentWordNum = 0
            this.soundPlay(nextSound)
          }
        }else{
          console.log('不正解です')
          this.soundPlay(ngSound)
          ++this.missNum
          console.log('現在回答の文字数目：' + curretWordNum)
        }
      })
    },
    soundPlay: function(sound) {
      sound.currentTime = 0
      sound.play()
    },
    countTimer: function(){
      const endSound = new Audio('../sounds/going-played2.mp3')
      let timer = window.setInterval(() => {
        this.timerNum -= 1

        if(this.timerNum <= 0){
          this.isEnd = true
          window.clearInterval(timer)
          endSound.play()
        }
      }, 1000)
    }
  }
}
</script>