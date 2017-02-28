Ling best fit swap algorithm
===============================
2017-02-19


Initial conditions
=======================
We have items that we want to put inside containers.

There are different types of containers, each having a maximum weight capacity
and a maximum volume capacity.

Each item have a weight and volume properties.

There is a manager, which goal is to distribute a given number of items into the minimum
number of containers.

The manager can get as many containers as needed to fulfil her mission.
 
 
The goal of the algorithm below is to help the manager with her task.


Important note: as I'm writing this initial conditions, I realize that for the
real case this algorithm tries to solve, the real goal is probably to import
the merchandises (items) with the minimum COST;

which makes me think that probably the optimization part of the remaining space within each container 
doesn't matter as much as the NUMBER of containers being used (as I believe my manager pays on a per container
base, and also she pays the weight and/or volume she uses, but that last criteria doesn't depend on HOW the items
are distributed WITHIN a container).


Algorithm overview
=====================

There is an algorithm known as best fit, which distribute items with a given height into a box with a maximum
height. Well, my problem is a little bit complicated than that because it deals with two properties (weight and
volume) instead of just one. Plus, there are different container types.


### Number of containers
The first step of this algorithm finds the best (maximized) combination of containers, that is,
the combination of containers that returns the minimum combined volume (i.e. if you add their volume together).
 
I'm ignoring the weight data for this step because the weight IS JUST A CONSEQUENCE of the volume.


### Containers treated in parallel
When that combination is used, I use a best fit like approach to distribute the items in the containers.
More precisely, I treat each container in parallel instead of treating one TO THE END, THEN ONLY treat the next one.
 
This technique is based on the belief that it's easier to put small items in a space than big items.

Before I treat the items, I sort them by decreasing volume, so I know that the biggest items will be treated first
(and the smallest last).
If I know that 3 containers will be used anyway (as the result of the first step), 
and if I have 5 items A, B, C, D and E; then I will put item A in container 1, then item B in container 2,
then item C in container 3, then item D in container 1, and finally item E in container 2.

This rather than putting (for instance) A, B and C in container 1, D in container 2, and E in container 3.

That's because of my belief that it's easier to deal with smallest items in the end, and so if for instance
A, B and C were 3 big items, and D and E were two smaller items, then after reparting A, B and C in one container
each, we are left with more space in each container.

That's just a personal belief though, so think about it before using this algorithm for yourself.


### Measurement of the wasted space, and swapping

When the containers are filled with the items, I wanted to go one step further and try to maximize the space used
per container.
As I said in the important note of the intro, this step is maybe not necessary as my manager won't pay less 
for doing so; therefore that part might be skipped depending on the problem's constraints.

The idea I had for optimizing the space was basically this:

- choose a container to study (for instance container 1), let's call it working container
- then try to swap two items, one belonging in the working container, and one belonging to ANOTHER container
- measure if the wasted space of the working has been improved
- if so, repeat the operation

The nice thing with this swapping algorithm is that it can be customized.
For instance, we can choose whether we swap the biggest items or the smallest, or random items, or a combination
of three, or more.

We can also choose how many items we want to swap before measuring the wasted space.
For instance, instead of swapping 1 item, we could swap 10 items.

Then, we can choose how many rounds of swapping we want to test before interpreting the result as final.
Whic means we could repeat the process of swapping 10 items three times and take only the best round (the one
that yields the minimum wasted space).

So, this swapping technique is basically a try and error technique (a naive one since I'm not mathematician,
but I think it's not bad).

The key point for this swapping technique is to be able to measure the remaining space of a container,
which is an easy thing to do.


### Ignoring volume, and swapping again

But so far, my algorithm ignored the weight parameter.
That's a voluntary decision.
That's because it's too complex for me to deal with those two data at the same time,
so I figured that I would first do my algorithm based on the volume (which seems more important to
me, but that's just a personal feeling), and then check whether or not any container is overloaded
by weight.

So, what if it turns out a container's maximum weight capacity has been passed?
Swapping again (that's all I could think of).

Swapping, but with the goal of re-establish normal weight conditions.
So, swapping is an important part of this algorithm.



Algorithm details
======================

So now that I've talked a bit about the different parts of the algorithm,
I can now try to expose the algorithm in pseudo code.


Part 1: finding the best combination of containers
--------------------------------------------------

My thinking is based on this example:

if
    item1=3
    item2=2
    container1.max = 4
    container2.max = 2
then
    then best combo is:
    use container1 and container2
    
BUT if
    item1=3
    item2=4
    container1.max = 4
    container2.max = 2
then
    then best combo is:
    use container1 and container1 (again)




Let containers be an array of containers.
Let items be an array of volume decreasing ordered items.



totalItemsVolume = sumVolumeOf(items)   // (sumVolumeOf has to be implemented of course)
orderedContainers = order containers by decreasing max volume


// gets the biggest container that can contain a full volume (no remaining space) of items
function getMaxContainer(array orderedContainers, remainingVolume){
    foreach orderedContainers as c 
        if remainingVolume - c.vol > 0
            return c
    return false        
}

function getMinContainer(array orderedContainers, remainingVolume){
    maxIndex = orderedContainers.length - 1
    for i = maxIndex, i>=0, i--
        c = orderedContainers[i]
            if remainingVolume - c.vol >= 0
                return c
    return false        
}


containersToUse = []
while false !== c = getMaxContainer( orderedContainers, remainingVolume )
    remainingVolume -= c.vol
    containersToUse.push(c)


if false !== c = getMinContainer (orderedContainers, remainingVolume)
    containersToUse.push(c)



    
    















 
























