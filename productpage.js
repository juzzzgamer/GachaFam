let valueCount=1;
let increment=document.querySelector(".btn-increment");
let decrement=document.querySelector(".btn-decrement");
let  count =document.querySelector("#quantity");
let  totalcount =document.querySelector("#price");
increment.addEventListener("click",()=>{
    let add=  valueCount++;
    count.value=add;
    totalcount.innerHTML=add*5; $
     })
     decrement.addEventListener("click",()=>{
     if(valueCount>=2){
      let sub =--valueCount;
      count.value=sub;
      totalcount.innerHTML=sub*5; $
     }
  
     })