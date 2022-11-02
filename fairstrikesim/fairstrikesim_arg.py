import subprocess
import csv
import sys
from datetime import datetime

rounds_to_run = 1000

scenario_file = sys.argv[1]

# header = [
#   'scenario'
# , 'players'
# , 'strikes'
# , 'runs'
# , 'max_player_count'
# , 'strikes_2_1'
# , 'strikes_2_2'
# , 'strikes_3_1'
# , 'strikes_3_2'
# , 'strikes_3_3'
# , 'strikes_4_1'
# , 'strikes_4_2'
# , 'strikes_4_3'
# , 'strikes_4_4'
# , 'variablestring'
# , 'avg_rounds'
# , 'avg_games_per_round'
# , 'total_meaningful_games'
# , 'total_meaningful_games_rounded'
# , 'extract_datetime'
# ]


outputfile = 'C:/Users/corey.hulse/Documents/Personal/FairStrike/strikeoutput_' + scenario_file + '.csv'

# with open(outputfile, 'w', encoding='UTF8', newline="") as f:
#     writer = csv.writer(f)
#
#     writer.writerow(header)


filename = 'C:/Users/corey.hulse/Documents/Personal/FairStrike/strikescenarios_' + scenario_file + '.csv'

with open(filename, 'r') as csvfile:
    datareader = csv.reader(csvfile)
    next(csvfile)

    for row in datareader:
        scenario = row[0]
        scenario_file_name = scenario.replace(" ", "_")
        outputfile = 'C:/Users/corey.hulse/Documents/Personal/FairStrike/strikeoutput_' + scenario_file_name + '.csv'
        p = row[1]
        s = row[2]
        r = rounds_to_run
        g = row[3]
        c21 = row[4]
        c22 = row[5]
        c31 = row[6]
        c32 = row[7]
        c33 = row[8]
        c41 = row[9]
        c42 = row[10]
        c43 = row[11]
        c44 = row[12]
        now = datetime.now()



        variablestring = " -p" + str(p) + " -s" + str(s) + " -r" + str(r) + " -g" + str(g) + " -x -c " + str(c21) + " " + str(c22) + " " + str(c31) + " " + str(c32) + " " + str(c33) + " " + str(c41) + " " + str(c42) + " " + str(c43) + " " + str(c44)

        variableoutput = str(p) + "," + str(s) + "," + str(r) + "," + str(g) + "," + str(c21) + "," + str(c22) + "," + str(c31) + "," + str(c32) + "," + str(c33) + "," + str(c41) + "," + str(c42) + "," + str(c43) + "," + str(c44) + ","

        location = "C:/Users/corey.hulse/Documents/Personal/FairStrike/FairStrikeSimV2.exe"

        subprocessrun = location + variablestring

        subprocess.call(subprocessrun, shell=True)
        subprocessoutput = subprocess.check_output(subprocessrun, shell=True)

        subprocessoutput = subprocessoutput.decode("utf-8")

        subprocesssplit = subprocessoutput.split("\t")

        subprocessoutput = subprocessoutput.replace("\t", ",")

        subprocesssplitround = round(float(subprocesssplit[3]))

        commaoutput = variableoutput + subprocessoutput

        scenario_strike_key = scenario_file_name + '_' + str(s)

        if str(p) == '2':
            print('Scenario: ' + scenario_file_name + ' | Strikes: ' + str(s))

        # scenario_key = str(scenario) + "_" + str(s)
        #
        # if scenario_key = scenario_key_prior:
        #     if subprocesssplitround >= subprocesssplitround_prior:
        #         subprocesssplitroundstep = subprocesssplitround
        #     else:
        #         subprocesssplitroundstep = subprocesssplitround_prior


        #print(commaoutput)

        data = [
          scenario
        , s
        , scenario_strike_key
        , p
        , r
        , g
        , c21
        , c22
        , c31
        , c32
        , c33
        , c41
        , c42
        , c43
        , c44
        , variablestring
        , subprocesssplit[1]
        , subprocesssplit[2]
        , float(subprocesssplit[3])
        , subprocesssplitround
        , now
        ]



        with open(outputfile, 'a', encoding='UTF8', newline="") as f:
            writer = csv.writer(f)

            # write the data
            writer.writerow(data)

        #  Key Prior Scenario
        scenario_key_prior = str(scenario) + "_" + str(s)

        subprocesssplitround_prior = round(float(subprocesssplit[3]))
