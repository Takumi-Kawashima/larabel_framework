<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ title }} <span class="badge badge-success">{{ category_name }}</span></div>

                    <div class="card-body text-center drill-body">
                      <button class="btn btn-primary" @click="doDrill" v-if="!isStarted">
                        START
                      </button>
                      <p v-if="isCountDown" style="font-size: 100px;">{{ countDownNum }}</p>

                      <template v-if="!isStarted && !isCountdown && !isEnd">
                        <p>{{ timerNum }}</p>
                        <span v-for="(word, index) in problemWords" :class="{'text-primary': index < currentWordNum}">{{ word }}</span>
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
        countDownNum: 3, //カウントダウン
        timerNum: 30, //タイマー
        missNum: 0, //ミス数
        wpm: 0, //WPM(１分間にどれだけできたか)
        isStarted: false,
        isEnd: false,
        isCountDown: false,
        currentWordNum: 0, //現在回答中の文字数目
        currentProblemNum: 0, //現在の問題番号
      }
    },
    computed: {
      //問題テキスト
      problemText: function(){
        return this.drill['problem' + this.currentProblemNum]
      },
      problemWords: function(){
        return Array.from(this.drill['problem' + this.currentProblemNum])
      },
      problemKeyCodes: function(){
        if(!Array.from(this.drill['problem' + this.currentProblemNum]).length){
          return null
        }

        //テキストから問題のキーコード配列を作成
        let problemKeyCodes = []
        console.log(Array.from(this.drill['problem' + this.currentProblemNum]))
        Array.from(this.drill['problem' + this.currentProblemNum]).forEach(
          (text) => {
            $.each(keyCodeMap, (keyText, keyCode) => {
              console.log('keyText:' + keyText)
              console.log('keyCode:' + keyCode)
              if(text === keyText){
                problemKeyCodes.push(keyCode);
              }
            })
          })
          console.log(problemKeyCodes)
          return problemKeyCodes
      },
      //問題の文字数
      totalWordNum: function(){
        return this.problemKeyCodes.length
      },
      //タイピングスコア
      typingScore: function(){
        console.log(this.wpm * 2)
        console.log((1 - this.missNum) / (this.wpm * 2))
        console.log((this.wpm * 2) * (1 - this.missNum / (this.wpm * 2)))
        return (this.wpm * 2) * (1 - this.missNum / (this.wpm * 2))
      }
    },
    methods: {
      doDrill: function(){
        this.isStarted = true
        this.countDown()
      },
      countDown: function(){
        const countSound = new Audio('../sounds/Countdown01-5.mp3')
        const startSound = new Audio('../sounds/Countdown01-6.mp3')

        this.isCountDown = true
        this.soundPlay(countSound)

        let timer = window.setInterval(() => {
          this.countDownNum -= 1

          if(this.countDownNum <= 0){
            this.isCountDown = false
            this.soundPlay(startSound)

            window.clearInterval(timer)
            this.countTimer()
            this.showFirstProblem()

            return
          }

          this.soundPlay(countSound)
        }, 1000)
      },
      showFirstProblem: function(){
        //効果音読み込み
        const okSound = new Audio('../sounds/punch-middle2.mp3')
        const ngSound = new Audio('../sounds/sword-clash4.mp3')
        const nextSound = new Audio('../sounds/punch-high2.mp3')

        //入力イベント時に入力キーと回答キーをチェック
        $(window).on('keypress', e => {
          console.log('何押した？：' + e.which)
          if(e.which === this.problemKeyCodes[this.currentWordNum]){
            console.log('正解！')

            this.soundPlay(okSound)

            ++this.currentWordNum
            ++this.wpm
            console.log('現在回答の文字数目:', + this.currentWordNum)

            //全文字正解終わったら次の問題へ
            if(this.totalWordNum === this.currentWordNum){
              console.log('次の問題へ！')
              ++this.currentProblemNum
              this.currentWordNum = 0

              this.soundPlay(nextSound)
            }
          }else{
            console.log('不正解です。。。')

            this.soundPlay(ngSound)
            ++this.missNum
            console.log('現在回答の文字数目:' + this.currentWordNum)
          }
        })

      },
      soundPlay: function(sound){
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
